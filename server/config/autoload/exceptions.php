<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            App\Exception\Handler\ValidationExceptionHandler::class, //验证器异常监听
            App\Exception\Handler\JwtExceptionHandler::class, //Jwt异常监听
            App\Exception\Handler\BusinessExceptionHandler::class, //业务异常监听
            App\Exception\Handler\RateLimitExceptionHandler::class, //限流
//            \Hyperf\ExceptionHandler\Handler\WhoopsExceptionHandler::class,//Whoops
        ],
    ],
];
