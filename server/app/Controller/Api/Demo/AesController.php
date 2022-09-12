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

use App\Controller\AbstractController;
use App\Request\AesRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\RateLimit\Annotation\RateLimit;
use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;

/**
 * Aes 加密demo.
 * @AutoController(prefix="api/demo/aes")
 */
class AesController extends AbstractController
{
    /**
     * @Inject
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
        return ['QPS 1, 峰值3'];
    }

    public function goDemo()
    {
        $parallel = new Parallel(5);
        for ($i = 0; $i < 20; ++$i) {
            $parallel->add(function () {
                var_dump(Coroutine::id() . '-' . time());
                sleep(1);
                return Coroutine::id();
            });
        }

        try {
            $results = $parallel->wait();
        } catch (ParallelExecutionException $e) {
            // $e->getResults() 获取协程中的返回值。
            // $e->getThrowables() 获取协程中出现的异常。
        }
    }

    public function test()
    {
        $cid = Coroutine::id();
        $name = Context::get('name');
        sleep(1);
        echo "CID:{$cid},Name:{$name}" . PHP_EOL;
    }
}
