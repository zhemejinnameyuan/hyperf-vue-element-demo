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
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\WebSocketServer\Sender;
use Psr\Container\ContainerInterface;

/**
 * 抽奖demo.
 * @AutoController(prefix="api/demo/lottery")
 */
class LotteryController extends AbstractController
{
    /**
     * @Inject
     * @var Sender
     */
    protected $sender;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 状态
     * @todo 奖品数量验证，奖品记录
     */
    public function index(): object
    {
        $prize_arr = [
            ['id' => 1, 'prize' => '200元万里通积分', 'v' => 5],
            ['id' => 2, 'prize' => '288元万里通积分', 'v' => 5],
            ['id' => 3, 'prize' => '250元万里通积分', 'v' => 5],
            ['id' => 4, 'prize' => '10元万里通积分', 'v' => 20],
            ['id' => 5, 'prize' => '谢谢参与', 'v' => 50],
            ['id' => 6, 'prize' => '88元万里通积分', 'v' => 5],
            ['id' => 7, 'prize' => '100元万里通积分', 'v' => 5],
            ['id' => 8, 'prize' => '888元万里通积分', 'v' => 1],
        ];

        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = self::getRand($arr); //根据概率获取奖项id

        $prizeArr = $prize_arr[$rid - 1];
        unset($prizeArr['v']);

        //统计奖品中奖次数
        $this->redisClient->hIncrBy('prize', "{$prizeArr['prize']}", 1);

        $msg = '谢谢参与';
        if ($prizeArr['prize'] != '谢谢参与') {
            $msg = '恭喜你，抽中:' . $prizeArr['prize'];
        }

        //发送广播消息
        $this->sendAll($msg);

        return response_success($msg, $prizeArr);
    }

    /**
     * 手动关闭连接.
     * @return string
     */
    public function close(int $fd)
    {
        go(function () use ($fd) {
            sleep(1);
            $this->sender->disconnect($fd);
        });

        return '';
    }

    protected static function getRand(array $proArr): int
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            }
            $proSum -= $proCur;
        }
        unset($proArr);
        return intval($result);
    }

    /**
     * 手动发送消息.
     * @param $content
     */
    protected function sendAll($content)
    {
        $roomKey = 'ws:lottery';
        $fds = $this->redisClient->sMembers($roomKey);

        foreach ($fds as $fd) {
            $this->sender->push(intval($fd), $content);
        }
    }
}
