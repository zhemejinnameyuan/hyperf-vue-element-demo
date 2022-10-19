<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller\Api\System;

use App\Constants\OpBusinessType;
use App\Controller\AbstractController;
use App\Model\Admin\SysMenuModel;
use App\Model\Admin\SysUserGroupModel;
use App\Model\Admin\SysUserModel;
use App\Request\System\AuthGroupRequest;
use Casbin\Enforcer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @Controller(prefix="api/system/authGroup")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class AuthGroupController extends AbstractController
{
    /**
     * @Inject
     * @var AuthGroupRequest
     */
    public $authGroupRequest;

    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     *  权限分组-获取分页数据.
     * @RequestMapping(path="", methods="GET")
     */
    public function getAuthGroup(): object
    {
        $where = $this->request->inputs(['status']);
        $dataList = SysUserGroupModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 权限分组-新增.
     * @RequestMapping(path="", methods="POST")
     */
    public function addAuthGroup(Enforcer $enforcer): object
    {
        $this->authGroupRequest->scene('add')->validateResolved();

        $saveData = $this->request->inputs(['name', 'status', 'menu_ids']);

        //新增
        $existInfo = SysUserGroupModel::query()->where('name', $saveData['name'])->count();
        if ($existInfo) {
            return response_error('名称已被使用,请更换再试');
        }

        $authGroupId = SysUserGroupModel::insertData($saveData);

        if ($authGroupId !== false) {
            //删除原有的权限，再重新赋值新的权限
            $enforcer->deletePermissionsForUser((string) $authGroupId);

            //获取菜单下所有api_path,再设置权限
            $apiPath = SysMenuModel::getApiPath($saveData['menu_ids']);
            foreach ($apiPath as $path) {
                $enforcer->addPermissionForUser((string) $authGroupId, $path, 'all');
            }

            $this->addOpLog($this->opBusinessType, (int) $authGroupId, '新增权限分组:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 权限分组-更新.
     * @RequestMapping(path="", methods="PUT")
     */
    public function updateAuthGroup(Enforcer $enforcer): object
    {
        $this->authGroupRequest->scene('update')->validateResolved();

        $saveData = $this->request->inputs(['id', 'name', 'status', 'menu_ids']);

        //更新
        $result = SysUserGroupModel::updateData($saveData['id'], $saveData);

        if ($result !== false) {
            //删除原有的权限，再重新赋值新的权限
            $enforcer->deletePermissionsForUser((string) $saveData['id']);

            //获取菜单下所有api_path,再设置权限
            $apiPath = SysMenuModel::getApiPath($saveData['menu_ids']);
            foreach ($apiPath as $path) {
                $enforcer->addPermissionForUser((string) $saveData['id'], $path, 'all');
            }

            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '更新权限分组:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 权限分组-删除.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function deleteAuthGroup(Enforcer $enforcer): object
    {
        $id = $this->request->input('id');
        $this->authGroupRequest->scene('delete')->validateResolved();

        $count = SysUserModel::where('group_id', '=', $id)->count();
        if ($count > 0) {
            return response_error('改分组下面还有使用中的用户，不能被删除');
        }

        $result = SysUserGroupModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            //删除权限分组下所有用户
            $enforcer->deleteRole((string) $id);

            $this->addOpLog($this->opBusinessType, (int) $id, '删除权限分组');
            return response_success();
        }
        return response_error();
    }

    /**
     * 获取权限分组select option.
     * @RequestMapping(path="optionList", methods="GET")
     */
    public function optionList(): object
    {
        $dataList = SysUserGroupModel::query()->get();

        return response_success('success', $dataList);
    }

    /**
     * 获取所有菜单节点.
     * @RequestMapping(path="menuTree", methods="GET")
     */
    public function menuTree(): object
    {
        $dataList = SysMenuModel::getMenuTree(0, 0);

        return response_success('success', $dataList);
    }

    /**
     * 刷新权限节点.
     * @RequestMapping(path="refreshNode", methods="POST")
     */
    public function refreshNode(Enforcer $enforcer): object
    {
        $dataList = SysUserGroupModel::query()->get();

        foreach ($dataList as $item) {
            //删除原有的权限，再重新赋值新的权限
            $enforcer->deletePermissionsForUser((string) $item['id']);

            //获取菜单下所有api_path,再设置权限
            $apiPath = SysMenuModel::getApiPath($item['menu_ids']);
            foreach ($apiPath as $path) {
                $enforcer->addPermissionForUser((string) $item['id'], $path, 'all');
            }
        }

        return response_success();
    }
}
