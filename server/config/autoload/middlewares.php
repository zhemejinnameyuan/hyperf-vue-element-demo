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
return [
    'http' => [
        //        Hyperf\Session\Middleware\SessionMiddleware::class,
        \Hyperf\Tracer\Middleware\TraceMiddleware::class, //调用链追踪
        Hyperf\Validation\Middleware\ValidationMiddleware::class, //验证器
        \App\Middleware\CorsMiddleware::class, //跨域
        //        Phper666\JWTAuth\Middleware\JWTAuthMiddleware::class,//jwt
                \App\Middleware\CasbinMiddleware::class,//权限
    ],
];
