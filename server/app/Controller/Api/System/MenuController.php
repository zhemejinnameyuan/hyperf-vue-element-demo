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
use App\Request\System\MenuRequest;
use Casbin\Enforcer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * 菜单管理.
 * @Controller(prefix="api/system/menu")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class MenuController extends AbstractController
{
    /**
     * @Inject
     * @var MenuRequest
     */
    public $menuRequest;

    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 菜单-获取分页数据.
     * @RequestMapping(path="", methods="GET")
     */
    public function getMenu(): object
    {
        $where = $this->request->inputs(['pid', 'status']);
        $dataList = SysMenuModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 菜单-新增.
     * @RequestMapping(path="", methods="POST")
     */
    public function addMenu(Enforcer $enforcer): object
    {
        $this->menuRequest->scene('add')->validateResolved();

        $saveData = $this->request->inputs(['component', 'redirect', 'icon', 'id', 'name', 'title', 'path', 'pid', 'status', 'sort', 'api_path', 'type']);
        //新增
        $result = SysMenuModel::insertData($saveData);

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], '新增菜单:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 菜单-更新.
     * @RequestMapping(path="", methods="PUT")
     */
    public function updateMenu(Enforcer $enforcer): object
    {
        $this->menuRequest->scene('update')->validateResolved();

        $saveData = $this->request->inputs(['component', 'redirect', 'icon', 'id', 'name', 'title', 'path', 'pid', 'status', 'sort', 'api_path', 'type']);
        //更新
        $result = SysMenuModel::updateData($saveData['id'], $saveData);

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], '更新菜单:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 菜单-删除.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function deleteMenu(): object
    {
        $this->menuRequest->scene('delete')->validateResolved();
        $id = $this->request->input('id');
        //查看是否有下级
        $isChildren = SysMenuModel::query()->where('pid', $id)->count();
        if ($isChildren) {
            return response_error('该菜单还有下级', $isChildren);
        }

        $result = SysMenuModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$id, '删除菜单');
            return response_success();
        }
        return response_error();
    }
}
