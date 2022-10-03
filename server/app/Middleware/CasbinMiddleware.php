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
        $server = $request->getServerParams();
        $path = strtolower($server['path_info']);
        //白名单url
        $whiteUrl = [
            '/api/user/getmenulist',
            '/api/user/info',
            '/api/user/logout',
            '/api/public/login',
        ];

        if (in_array($path, $whiteUrl)) {
            return $handler->handle($request);
        }

        $userInfo = $this->jwt->getParserData();

        $superAdmin = env('SUPER_ADMIN_ID'); //超管ID，放行
//        if ($userInfo['user_id'] == $superAdmin || Enforcer::enforce($userInfo['username'], $path, 'all')) {
        return $handler->handle($request);
//        }

        return response_error('无权进行该操作:' . $path);
    }
}
