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
use Casbin\Enforcer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Container\ContainerInterface;

/**
 * Aes 加密demo.
 * @AutoController(prefix="api/demo/casbin")
 */
class CasbinController extends AbstractController
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

    public function demo(Enforcer $enforcer)
    {
        //添加角色
//        $enforcer->addRoleForUser($this->getUserName(), '管理员');
//        $enforcer->addRoleForUser('test', '管理员');
//
//        //为角色添加权限
        $path = $this->request->input('path');
        $method = $this->request->input('method');
        $enforcer->addPermissionForUser('管理员', $path, $method);

        return $enforcer->getUsersForRole('管理员');
        return $enforcer->enforce($this->getUserName(), '/api/test', 'edit');
    }

    public function demo2()
    {
        return 'demo2';
    }
}
