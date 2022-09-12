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

use App\Amqp\Producer\DemoProducer;
use App\Controller\AbstractController;
use Hyperf\Amqp\Producer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Container\ContainerInterface;

/**
 * Amqp Demo.
 * @AutoController(prefix="api/demo/amqp")
 */
class AmqpController extends AbstractController
{
    /**
     * @Inject
     * @var Producer
     */
    protected $producer;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 入口.
     */
    public function index()
    {
        $data = [
            'name' => '111',
            'time' => date('Y-m-d H:i:s'),
        ];

        $message = new DemoProducer($data);
        return $this->producer->produce($message);
    }
}
