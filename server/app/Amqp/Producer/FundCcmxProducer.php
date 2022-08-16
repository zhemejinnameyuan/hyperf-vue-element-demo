<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * 基金持仓明细 生产者
 * @Producer(exchange="hyperf-fundccmx", routingKey="hyperf-fundccmx")
 */
class FundCcmxProducer extends ProducerMessage
{
    public function __construct($data)
    {
        $this->payload = $data;
    }
}
