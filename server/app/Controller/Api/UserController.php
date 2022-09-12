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
use App\Model\Admin\OpLogModel;
use App\Model\Admin\SysMenuModel;
use App\Model\Admin\SysUserGroupModel;
use App\Model\Admin\SysUserModel;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @AutoController(prefix="api/user")
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

    /** ================== 登录后获取信息 ================== */

    /**
     * 退出.
     */
    public function logout(): object
    {
        return response_success('success' . $this->jwt->logout());
    }

    /**
     * 获取token.
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
     */
    public function getMenuList(): object
    {
        $menuOriData = SysMenuModel::getMenuTree($this->getUserId());
        return response_success('success', $menuOriData);
    }

    /** ================== 用户管理 ================== */

    /**
     *  用户-获取分页数据.
     */
    public function userDataList(): object
    {
        $where = $this->request->inputs(['username', 'group_id', 'status']);
        $dataList = SysUserModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 用户-删除.
     */
    public function userDelete(): object
    {
        $id = $this->request->input('id');
        if (! $id) {
            return response_error('缺少ID参数');
        }

        if ($id == env('SUPER_ADMIN_ID', 1)) {
            return response_error('不能对管理员进行删除操作');
        }

        $result = SysUserModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $id, '删除用户');
            return response_success('操作成功');
        }
        return response_error('操作失败');
    }

    /**
     * 用户-保存.
     */
    public function userSave(): object
    {
        $this->validationCheck(
            [
                'username' => 'required',
                'nickname' => 'required',
                'group_id' => 'required',
                //                'google_auth_key' => 'required|size:16',
                'password' => 'required_without:id',
            ],
            [
                'username.required' => '账号不能为空',
                'nickname.required' => '昵称不能为空',
                'group_id.required' => '请选择分组',
                //                'google_auth_key.required' => '请生成google密钥',
                //                'google_auth_key.size' => 'google密钥长度不合法',
                'password.required_without' => '请输入密码',
            ]
        );

        $saveData = $this->request->inputs(['id', 'username', 'nickname', 'password', 'group_id', 'status', 'password_error_count']);

        //密码加密
        $password = hyperf_md5($saveData['password'], env('ADMIN_LOGIN_KEY'));
        if ($saveData['id']) {
            if ($saveData['password'] == '') {
                unset($saveData['password']);
            } else {
                $saveData['password'] = $password;
            }

            //更新
            $result = SysUserModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existUser = SysUserModel::query()->where('username', $saveData['username'])->count();
            if ($existUser) {
                return response_error('用户名已被使用,请更换再试');
            }
            $saveData['password'] = $password;
            $result = SysUserModel::insertData($saveData);
        }

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '添加/更新 用户:' . json_encode($saveData));
            return response_success('操作成功');
        }
        return response_error('操作失败');
    }

    /** ================== 权限分组管理 ================== */

    /**
     *  权限分组-获取分页数据.
     */
    public function userGroupDataList(): object
    {
        $where = $this->request->inputs(['status']);
        $dataList = SysUserGroupModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 权限分组-删除.
     */
    public function userGroupDelete(): object
    {
        $id = $this->request->input('id');
        if (! $id) {
            return response_error('缺少ID参数');
        }

        $result = SysUserGroupModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $id, '删除 权限分组');
            return response_success('操作成功');
        }
        return response_error('操作失败');
    }

    /**
     * 权限分组-保存.
     */
    public function userGroupSave(): object
    {
        $this->validationCheck(
            [
                'name' => 'required',
            ],
            [
                'name.required' => '名称不能为空',
            ]
        );

        $saveData = $this->request->inputs(['id', 'name', 'status', 'rule_ids']);

        if ($saveData['id']) {
            //更新
            $result = SysUserGroupModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existInfo = SysUserGroupModel::query()->where('name', $saveData['name'])->count();
            if ($existInfo) {
                return response_error('名称已被使用,请更换再试');
            }

            $result = SysUserGroupModel::insertData($saveData);
        }

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '添加/更新 权限分组:' . json_encode($saveData));
            return response_success('操作成功');
        }
        return response_error('操作失败');
    }

    /**
     * 获取权限分组select option.
     */
    public function userGroupOptionDataList(): object
    {
        $dataList = SysUserGroupModel::query()->get();

        return response_success('success', $dataList);
    }

    /**
     * 获取所有菜单节点.
     */
    public function menuTree(): object
    {
        $dataList = SysMenuModel::getMenuTree(0);

        return response_success('success', $dataList);
    }

    /** ================== 操作日志 ================== */
    public function opLogDataList(): object
    {
        $where = $this->request->inputs(['op_username', 'business_id', 'content', 'business_type', 'date']);

        $dataList = OpLogModel::getDataList($where, $this->getPage(), $this->getLimit());

        if ($dataList['data']) {
            foreach ($dataList['data'] as &$item) {
                $item['business_type'] = OpBusinessType::getMessage($item['business_type']);
            }
        }

        return response_success('success', $dataList);
    }

    /**
     * 获取操作日志业务类型.
     * @return object|\Psr\Http\Message\ResponseInterface
     */
    public function opBusinessTypeDataList(): object
    {
        $dataList = [];
        try {
            $reflect = new \ReflectionClass(new OpBusinessType());
            if ($reflect) {
                foreach ($reflect->getConstants() as $key => $value) {
                    $dataList[] = [
                        'id' => $value,
                        'name' => OpBusinessType::getMessage($value),
                    ];
                }
            }
        } catch (\ReflectionException $exception) {
        }

        return response_success('success', $dataList);
    }
}
