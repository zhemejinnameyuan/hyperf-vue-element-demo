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
use App\Model\Admin\SysConfigModel;
use App\Request\System\ConfigRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @Controller(prefix="api/system/config")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class ConfigController extends AbstractController
{
    /**
     * @Inject
     * @var ConfigRequest
     */
    public $configRequest;

    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 配置-获取分页数据.
     * @RequestMapping(path="", methods="GET")
     */
    public function getConfig(): object
    {
        $where = $this->request->inputs(['key']);
        $dataList = SysConfigModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 配置-新增.
     * @RequestMapping(path="", methods="POST")
     */
    public function addConfig(): object
    {
        $this->configRequest->scene('add')->validateResolved();

        $saveData = $this->request->inputs(['key', 'value', 'desc', 'type', 'status']);

        if ($saveData['type'] == 'json' && ! json_decode($saveData['value'], true)) {
            return response_error('请填写正确的json格式');
        }

        //新增
        $result = SysConfigModel::insertData($saveData);

        if ($result !== false) {
            //重置配置
            parent::initConfig();
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '新增配置:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 配置-更新.
     * @RequestMapping(path="", methods="PUT")
     */
    public function updateConfig(): object
    {
        $this->configRequest->scene('update')->validateResolved();

        $saveData = $this->request->inputs(['key', 'value', 'desc', 'id', 'type', 'status']);

        if ($saveData['type'] == 'json' && ! json_decode($saveData['value'], true)) {
            return response_error('请填写正确的json格式');
        }
        //更新
        $result = SysConfigModel::updateData($saveData['id'], $saveData);
        if ($result !== false) {
            //重置配置
            parent::initConfig();
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '更新配置:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 配置-删除.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function deleteConfig(): object
    {
        $this->configRequest->scene('delete')->validateResolved();
        $id = $this->request->input('id');

        $result = SysConfigModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $id, '删除配置');
            return response_success();
        }
        return response_error();
    }
}
