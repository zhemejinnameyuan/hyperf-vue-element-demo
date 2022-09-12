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
namespace App\Controller\Api;

use App\Constants\UserCode;
use App\Controller\AbstractController;
use App\Model\Admin\SysUserModel;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Container\ContainerInterface;

/**
 * 公共控制器，不使用jwt中间件.
 * @AutoController(prefix="api/public")
 */
class PublicController extends AbstractController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 登录.
     */
    public function login(): object
    {
        //登录请求
        if ($this->request->isMethod('post')) {
            $this->validationCheck(
                [
                    'username' => 'required',
                    'password' => 'required',
                ],
                [
                    'username.required' => '请输入用户名',
                    'password.required' => '请输密码',
                ]
            );

            $username = common_aes_decrypt($this->request->input('username'));
            $password = hyperf_md5(common_aes_decrypt($this->request->input('password')), env('ADMIN_LOGIN_KEY'));

            if (! $username) {
                return response_error('账号数据错误');
            }
            if (! $password) {
                return response_error('密码数据错误');
            }

            $login = SysUserModel::login($username, $password);

            if ($login['code'] == UserCode::SUCCESS) {
                //登录成功
                $userInfo = $login['info'];

                //组装token
                $userData = [
                    'user_id' => $userInfo['id'],
                    'username' => $userInfo['username'],
                    'nickname' => $userInfo['nickname'],
                ];
                // 使用默认场景登录
                $token = $this->jwt->setScene('default')->getToken($userData);
                $data = [
                    'token' => $token,
                    'exp' => $this->jwt->getTTL(),
                ];

                return response_success('success', $data);
            }
            return response_error($login['msg']);
        }
    }

    /**
     * 获取系统信息.
     */
    public function systemInfo(): object
    {
        $data = [
            ['key' => 'PHP版本', 'value' => phpversion()],
            ['key' => '文件上传大小', 'value' => ini_get('upload_max_filesize')],
            ['key' => 'post大小', 'value' => ini_get('post_max_size')],
            ['key' => '服务器时间', 'value' => date('Y年n月j日 H:i:s')],
            ['key' => 'swoole版本', 'value' => phpversion('swoole')],
        ];

        return response_success('success', $data);
    }
}
