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
use App\Model\Admin\SysUserModel;
use App\Request\System\UserRequest;
use Casbin\Enforcer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @Controller(prefix="api/system/user")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserRequest
     */
    public $userRequest;

    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 用户-获取分页数据.
     * @RequestMapping(path="", methods="GET")
     */
    public function getUser(): object
    {
        $where = $this->request->inputs(['username', 'group_id', 'status']);
        $dataList = SysUserModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 用户-新增.
     * @RequestMapping(path="", methods="POST")
     */
    public function addUser(Enforcer $enforcer): object
    {
        $this->userRequest->scene('add')->validateResolved();

        $saveData = $this->request->inputs(['username', 'nickname', 'password', 'group_id', 'status', 'password_error_count']);

        if ($saveData['id']) {
            if ($saveData['password'] == '') {
                unset($saveData['password']);
            } else {
                $saveData['password'] = password_hash($saveData['password'], PASSWORD_DEFAULT);
            }

            //更新
            $result = SysUserModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existUser = SysUserModel::query()->where('username', $saveData['username'])->count();
            if ($existUser) {
                return response_error('用户名已被使用,请更换再试');
            }
            $saveData['password'] = password_hash($saveData['password'], PASSWORD_DEFAULT);
            $result = SysUserModel::insertData($saveData);
        }

        if ($result !== false) {
            //删除用户所有权限
            $enforcer->deleteRolesForUser((string) $saveData['username']);
            //为用户添加权限组
            $enforcer->addRoleForUser((string) $saveData['username'], (string) $saveData['group_id']);

            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '添加/更新 用户:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 用户-更新.
     * @RequestMapping(path="", methods="PUT")
     */
    public function updateUser(Enforcer $enforcer): object
    {
        $this->userRequest->scene('update')->validateResolved();

        $saveData = $this->request->inputs(['id', 'username', 'nickname', 'password', 'group_id', 'status', 'password_error_count']);

        if ($saveData['id']) {
            if ($saveData['password'] == '') {
                unset($saveData['password']);
            } else {
                $saveData['password'] = password_hash($saveData['password'], PASSWORD_DEFAULT);
            }

            //更新
            $result = SysUserModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existUser = SysUserModel::query()->where('username', $saveData['username'])->count();
            if ($existUser) {
                return response_error('用户名已被使用,请更换再试');
            }
            $saveData['password'] = password_hash($saveData['password'], PASSWORD_DEFAULT);
            $result = SysUserModel::insertData($saveData);
        }

        if ($result !== false) {
            //删除用户所有权限
            $enforcer->deleteRolesForUser((string) $saveData['username']);
            //为用户添加权限组
            $enforcer->addRoleForUser((string) $saveData['username'], (string) $saveData['group_id']);

            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '添加/更新 用户:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 用户-删除.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function deleteUser(Enforcer $enforcer): object
    {
        $id = $this->request->input('id');

        $this->userRequest->scene('delete')->validateResolved();

        if ($id == env('SUPER_ADMIN_ID', 1)) {
            return response_error('不能对管理员进行删除操作');
        }

        $userInfo = SysUserModel::query()->find($id);
        $result = SysUserModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            //删除用户所有权限
            $enforcer->deleteRolesForUser((string) $userInfo['username']);

            $this->addOpLog($this->opBusinessType, (int) $id, '删除用户');
            return response_success();
        }
        return response_error();
    }
}
