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
namespace App\Middleware;

use Donjan\Casbin\Enforcer;
use Hyperf\Di\Annotation\Inject;
use Phper666\JWTAuth\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CasbinMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var JWT
     */
    public $jwt;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userInfo = $this->jwt->getParserData();
//        $userName =  $userInfo ? string($userInfo['user_name']) : 0;
        $server = $request->getServerParams();
        $path = strtolower($server['path_info']);
        $method = strtoupper($server['request_method']);
        $superAdmin = 1;

        if ($userInfo['user_id'] === $superAdmin || (Enforcer::enforce($userInfo['username'], $path, $method))) {
            return $handler->handle($request);
        }

        return response_error('无权进行该操作');
    }
}
