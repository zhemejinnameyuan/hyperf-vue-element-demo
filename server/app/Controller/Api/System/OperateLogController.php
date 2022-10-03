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
use App\Model\Admin\OpLogModel;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * 操作日志.
 * @Controller(prefix="api/system/operateLog")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class OperateLogController extends AbstractController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 获取操作日志.
     * @RequestMapping(path="", methods="GET")
     */
    public function getOperateLog(): object
    {
        $where = $this->request->inputs(['op_username', 'business_id', 'content', 'business_type', 'date']);

        $dataList = OpLogModel::getDataList($where, $this->getPage(), $this->getLimit());

        if ($dataList['data']) {
            foreach ($dataList['data'] as &$item) {
                $item['business_type'] = OpBusinessType::getMessage($item['business_type']);
            }
        }

        return response_success('success', $dataList);
    }

    /**
     * 获取操作日志业务类型.
     * @RequestMapping(path="businessType", methods="GET")
     */
    public function getBusinessType(): object
    {
        $dataList = [];
        try {
            $reflect = new \ReflectionClass(new OpBusinessType());
            if ($reflect) {
                foreach ($reflect->getConstants() as $key => $value) {
                    $dataList[] = [
                        'id' => $value,
                        'name' => OpBusinessType::getMessage($value),
                    ];
                }
            }
        } catch (\ReflectionException $exception) {
        }

        return response_success('success', $dataList);
    }
}
