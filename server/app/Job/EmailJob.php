<?php


namespace App\Job;


use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;
use Hyperf\AsyncQueue\Job;

/**
 * 注解模式的队列
 * @package App\Job
 */
class EmailJob
{
    /**
     * @AsyncQueueMessage()
     */
    public function sendEmail($params)
    {
        var_dump($params);
    }
}