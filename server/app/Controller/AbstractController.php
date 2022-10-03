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
namespace App\Controller;

use App\Exception\ValidationException;
use App\Model\Admin\OpLogModel;
use App\Model\Admin\SysConfigModel;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Phper666\JWTAuth\JWT;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * 容器.
     * @var ContainerInterface
     */
    public $container;

    /**
     * 请求
     * @Inject
     * @var RequestInterface
     */
    public $request;

    /**
     * 响应.
     * @Inject
     * @var ResponseInterface
     */
    public $response;

    /**
     * redis客户端.
     * @var RedisFactory
     */
    public $redisClient;

    /**
     * @Inject
     * @var JWT
     */
    public $jwt;

    /**
     * 验证器.
     * @Inject
     * @var ValidatorFactoryInterface
     */
    public $validationFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->redisClient = $this->container->get(RedisFactory::class)->get('default');
        self::initConfig();
    }

    /**
     * 获取当前页码
     */
    protected function getPage(): int
    {
        return intval($this->request->input('currentPage', 0));
    }

    /**
     * 获取每页显示数量.
     */
    protected function getLimit(): int
    {
        return intval($this->request->input('pageSize', 15));
    }

    /**
     * 写日志.
     * @param int $business_type 业务类型
     * @param int $business_id 业务id
     * @param string $content 日志内容
     * @return mixed
     */
    protected function addOpLog(int $business_type = 0, int $business_id = 0, string $content = ''): void
    {
        $model = make(OpLogModel::class);

        $model->op_userid = $this->getUserId();
        $model->op_username = $this->getUserName();
        $model->business_type = $business_type;
        $model->business_id = $business_id;
        $model->content = $content;

        $model->save();
    }

    /**
     * 验证器.
     * @param $rules
     * @param $messages
     */
    protected function validationCheck(array $rules, array $messages): void
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            $rules,
            $messages
        );

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();

            throw new ValidationException($errorMessage, 500);
        }
    }

    /**
     * 获取用户id.
     */
    protected function getUserId(): int
    {
        $userInfo = $this->jwt->getParserData();
        return $userInfo ? (int) $userInfo['user_id'] : 0;
    }

    /**
     * 获取用户名.
     */
    protected function getUserName(): string
    {
        $userInfo = $this->jwt->getParserData();

        return $userInfo['username'] ?: '';
    }

    /**
     * 初始化配置.
     */
    protected static function initConfig(): void
    {
        $model = make(SysConfigModel::class);

        $dataList = $model::query()->get();

        if ($dataList) {
            foreach ($dataList as $item) {
                $configKey = $item['key'];
                $configValue = '';
                if ($item['status'] == 1) {
                    if ($item['type'] == 'text') {
                        $configValue = $item['value'];
                    } elseif ($item['type'] == 'json') {
                        $configValue = json_decode($item['value'], true);
                    }
                }

                logger('config')->info('config init', ['key' => $configKey, 'value' => $configValue]);
                config_set($configKey, $configValue);
            }
        }
    }
}
