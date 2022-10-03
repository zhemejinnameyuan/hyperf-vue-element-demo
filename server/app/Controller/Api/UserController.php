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

use App\Constants\OpBusinessType;
use App\Controller\AbstractController;
use App\Model\Admin\SysMenuModel;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @Controller(prefix="api/user")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class UserController extends AbstractController
{
    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 退出.
     * @RequestMapping(path="logout", methods="POST")
     */
    public function logout(): object
    {
        return response_success('success' . $this->jwt->logout());
    }

    /**
     * 登录后获取token.
     * @RequestMapping(path="info", methods="GET")
     */
    public function info(): object
    {
        $info = [
            'roles' => ['admin'],
            'introduction' => 'I am a super administrator',
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'name' => $this->jwt->getParserData()['username'],
        ];

        return response_success('success', $info);
    }

    /**
     * 获取左侧导航列表.
     * @RequestMapping(path="getMenuList", methods="GET")
     */
    public function getMenuList(): object
    {
        $menuOriData = SysMenuModel::getMenuTree($this->getUserId());
        return response_success('success', $menuOriData);
    }
}
