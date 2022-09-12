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
namespace App\Controller\Api\Demo;

use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * 签到demo.
 * @AutoController(prefix="api/demo/sign")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class SignController extends AbstractController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 入口.
     */
    public function index(): object
    {
        $startDate = date('Y-m-01');
        $todayDate = date('Y-m-d');
//        $todayDate = date("Y-m-05"); //手动设定打卡日期
        $month = date('Ym');
        $uid = $this->getUserId();
        $signCacheKey = "sign:{$month}:{$uid}";

        //签到功能
        $startTime = strtotime($startDate);
        $todayTime = strtotime($todayDate);

        $offset = intval(($todayTime - $startTime) / 86400);

        $exist = $this->redisClient->getBit($signCacheKey, $offset);

        if ($exist) {
            return response_error('今日已签到');
        }

        $this->redisClient->setBit($signCacheKey, $offset, true);

        return response_success('签到成功');
    }

    public function getSignDays()
    {
        $startDate = strtotime(date('Y-m-01'));
        $month = date('Ym');
        $uid = $this->getUserId();
        $signCacheKey = "sign:{$month}:{$uid}";

        $allSign = $this->redisClient->get($signCacheKey);

        $hex_str = unpack('H*', $allSign)[1];
        //处理补位
        $hex_str = str_pad(decbin(hexdec($hex_str)), PHP_INT_SIZE * 4, '0', STR_PAD_LEFT);

        //转换为打卡的日期
        $days = [];
        for ($i = 0; $i <= strlen($hex_str); ++$i) {
            $daySign = substr($hex_str, $i, 1);
            if ($daySign == 1) {
                $days[] = date('Y-m-d', strtotime("+{$i} day", $startDate));
            }
        }

        return response_success('success', $days);
    }
}
