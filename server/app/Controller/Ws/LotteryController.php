<?php


namespace App\Controller\Ws;


use App\Controller\AbstractController;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Psr\Container\ContainerInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

/**
 * 彩票通知 websocket
 * Class LotteryController
 * @package App\Controller\Ws
 */
class LotteryController extends AbstractController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    /**
     * redis key
     * @var string
     */
    protected $roomKey = 'ws:lottery';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }
    public function onOpen($server, Request $request): void
    {
        echo "fd:" . $request->fd . "-is-opened" . PHP_EOL;
        $server->push($request->fd, 'Opened');

        //记录连接ID 到redis
        $this->redisClient->sAdd($this->roomKey, $request->fd);
    }


    public function onClose($server, int $fd, int $reactorId): void
    {
        //删除id
        $this->redisClient->sRem($this->roomKey, $fd);

        var_dump('closed');
    }


    public function onMessage($server, Frame $frame): void
    {
        $roomKey = 'ws:lottery';
        $fds = $this->redisClient->sMembers($roomKey);

        foreach ($fds as $fd) {
            $server->push($fd, 'send: ' . $frame->data);
        }


    }
}
