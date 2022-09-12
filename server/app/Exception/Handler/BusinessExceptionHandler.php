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
namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BusinessExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        logger('server')->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        logger('server')->error($throwable->getTraceAsString());

        if ($throwable instanceof \App\Exception\ValidationException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            return response_error($throwable->getMessage(), '2', 400);
        }

        return response_error($throwable->getMessage(), '', $throwable->getCode());
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
