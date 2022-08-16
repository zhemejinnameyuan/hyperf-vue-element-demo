<?php

declare(strict_types=1);

namespace App\Controller\Api\Demo;

use App\Controller\AbstractController;
use App\Request\AesRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\RateLimit\Annotation\RateLimit;
use League\Flysystem\Filesystem;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Aes 加密demo
 * @AutoController(prefix="api/demo/aes")
 */
class AesController extends AbstractController
{

    /**
     * @Inject()
     * @var AesRequest
     */
    public $aesRequest;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }

    public function update()
    {
        $this->aesRequest->scene('update')->validateResolved();
    }

    public function delete()
    {
        $this->aesRequest->scene('delete')->validateResolved();
    }

    public function uploadFile(Filesystem $filesystem)
    {
        // Write Files
        return $filesystem->write('file.txt', 'contents');
    }

    /**
     * @RateLimit(create=1, consume=1, capacity=1)
     * @return array
     */
    public function rateLimit()
    {
        return ["QPS 1, 峰值3"];
    }
}
