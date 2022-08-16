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

namespace App\Exception\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Hyperf\Validation\Validator;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 验证器 异常类
 * Class ValidationExceptionHandler
 * @package App\Exception\Handler
 */
class ValidationExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 业务异常
        if ($throwable instanceof ValidationException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            return response_error($throwable->validator->errors()->first(), '', 422);
        }

        // 交给下一个异常处理器
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
