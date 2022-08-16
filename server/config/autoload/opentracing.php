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
use Zipkin\Samplers\BinarySampler;

return [
    'default' => env('TRACER_DRIVER', 'zipkin'),
    'enable' => [
        'guzzle' => env('TRACER_ENABLE_GUZZLE', true),
        'redis' => env('TRACER_ENABLE_REDIS', true),
        'db' => env('TRACER_ENABLE_DB', true),
        'method' => env('TRACER_ENABLE_METHOD', true),
    ],
    'tracer' => [
        'zipkin' => [
            'driver' => \Hyperf\Tracer\Adapter\ZipkinTracerFactory::class,
            'app' => [
                'name' => env('APP_NAME', 'hyperf-admin'),
                // Hyperf will detect the system info automatically as the value if ipv4, ipv6, port is null
                'ipv4' => '127.0.0.1',
                'ipv6' => null,
                'port' => 9501,
            ],
            'options' => [
                'endpoint_url' => env('ZIPKIN_ENDPOINT_URL', 'http://localhost:9411/api/v2/spans'),
                'timeout' => env('ZIPKIN_TIMEOUT', 1),
            ],
            'sampler' => BinarySampler::createAsAlwaysSample(),
        ],
    ],
];
