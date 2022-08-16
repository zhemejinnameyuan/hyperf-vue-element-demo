<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

return [
    'default' => [
        'driver' => 'mysql',
        'read' => [
            'host' => env('ADMIN_DB_HOSTNAME'),
        ],
        'write' => [
            'host' => env('ADMIN_DB_HOSTNAME'),
        ],
        'sticky'  => false,
        'port' => env('ADMIN_DB_HOSTPORT'),
        'database' => env('ADMIN_DB_DATABASE'),
        'username' => env('ADMIN_DB_USERNAME'),
        'password' => env('ADMIN_DB_PASSWORD'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 2,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache' => [
            'handler' => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key' => 'mc:%s:m:%s:%s:%s',
            'prefix' => 'default',
            'ttl' => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script' => true,
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
            ],
        ],
    ],
];
