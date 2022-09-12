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
namespace App\Job;

use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;

/**
 * 注解模式的队列.
 */
class EmailJob
{
    /**
     * @AsyncQueueMessage
     * @param mixed $params
     */
    public function sendEmail($params)
    {
        var_dump($params);
    }
}
