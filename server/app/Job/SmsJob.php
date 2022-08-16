<?php


namespace App\Job;


use Hyperf\AsyncQueue\Job;

/**
 * 传统模式的队列
 * Class SmsJob
 * @package App\Job
 */
class SmsJob extends Job
{
    public $params;

    public function __construct($params)
    {
        // 这里最好是普通数据，不要使用携带 IO 的对象，比如 PDO 对象
        $this->params = $params;
    }

    /**
     * 消费队列
     */
    public function handle()
    {
        // 根据参数处理具体逻辑
        var_dump($this->params);
    }
}