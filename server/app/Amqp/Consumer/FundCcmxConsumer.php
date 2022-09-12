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
namespace App\Amqp\Consumer;

use App\Model\Admin\FundCcmxModel;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use PhpAmqpLib\Message\AMQPMessage;
use QL\QueryList;

/**
 * 基金持仓明细 消费者.
 * @Consumer(exchange="hyperf-fundccmx", routingKey="hyperf-fundccmx", queue="hyperf-fundccmx", name="FundCcmxConsumer", nums=1)
 */
class FundCcmxConsumer extends ConsumerMessage
{
    /**
     * redis客户端.
     * @var RedisFactory
     */
    public $redisClient;

    /**
     * @Inject
     * @var QueryList
     */
    protected $queryList;

    public function consumeMessage($data, AMQPMessage $message): string
    {
        $res = $this->handle($data['code']);

        if ($res) {
            return Result::ACK;
        }
        return Result::NACK;
    }

    /**
     * 处理数据.
     * @param $fundCode
     * @return bool
     */
    public function handle($fundCode)
    {
        $this->redisClient = $this->container->get(RedisFactory::class)->get('default');
        $fundCcmxRedisKey = 'fund_ccmx';
        try {
            if (! $fundCode) {
                throw new \Exception('缺少参数');
            }
            if ($this->redisClient->hGet($fundCcmxRedisKey, $fundCode)) {
                throw new \Exception('已经执行过了');
            }

            $url = 'http://fundf10.eastmoney.com/FundArchivesDatas.aspx?type=jjcc&code=' . $fundCode . '&topline=20';
            $content = $this->queryList->get($url);

            $codeArr = $content->find('.box')->eq(0)->find('tbody tr .toc')->find('a')->texts()->all();

            $reportDate = $content->find('.box')->eq(0)->find('.px12')->eq(0)->text();

            $returnArr = [];
            if (! $codeArr) {
                $codeArr = $content->find('.box')->eq(0)->find('tbody tr ')->find('a')->texts()->all();

                if ($codeArr) {
                    $returnArr = array_chunk($codeArr, 6);
                }
            } else {
                $returnArr = array_chunk($codeArr, 2);
            }

            $newArr = [];
            foreach ($returnArr as $key => $value) {
                $data = [
                    'fund_code' => $fundCode,
                    'stock_code' => $value[0],
                    'stock_name' => $value[1],
                    'report_date' => $reportDate,
                ];

                FundCcmxModel::insertData($data);

                $newArr[$value[0]] = $value[1];
            }

            $this->redisClient->hSet($fundCcmxRedisKey, $fundCode, 1);

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
