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
namespace App\Model\Admin;

use App\Constants\UserCode;
use App\Model\Model;

/**
 * 用户
 * Class SysUserModel.
 */
class SysUserModel extends Model
{
    /**
     * 表名.
     */
    protected $table = 'sys_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * 获取用户数据.
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if ($where['username']) {
                $query->where('username', $where['username']);
            }
            if ($where['group_id'] && $where['group_id'] > -1) {
                $query->where('group_id', $where['group_id']);
            }
            if (isset($where['status']) && $where['status'] > -1) {
                $query->where('status', $where['status']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'desc')->get(),
        ];
    }

    /**
     * 登录.
     */
    public static function login(string $username, string $password): array
    {
        try {
            $info = parent::query()->where('username', $username)->first();

            if (! $info) {
                throw new \Exception(UserCode::getMessage(UserCode::NOT_FOUND), UserCode::NOT_FOUND);
            }

            if ($info['password_error_count'] >= UserCode::MAX_PASSWORD_ERROR) {
                throw new \Exception(UserCode::getMessage(UserCode::MAX_PASSWORD_ERROR), UserCode::MAX_PASSWORD_ERROR);
            }

            if ($info['password'] !== $password) {
                //更新密码错误次数
                self::updateUserInfo($info['id'], ['password_error_count' => $info['password_error_count'] + 1]);

                throw new \Exception(UserCode::getMessage(UserCode::PASSWORD_ERROR), UserCode::PASSWORD_ERROR);
            }
            if ($info['status'] !== 1) {
                throw new \Exception(UserCode::getMessage(UserCode::LOCKED), UserCode::LOCKED);
            }

            //更新最后登录IP
            self::updateUserInfo($info['id'], ['login_ip' => get_client_ip()]);

            return [
                'code' => UserCode::SUCCESS,
                'msg' => UserCode::getMessage(UserCode::SUCCESS),
                'info' => $info,
            ];
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'msg' => $exception->getMessage(),
            ];
        }
    }

    /**
     * 更新用户信息.
     */
    protected static function updateUserInfo(int $id, array $save): void
    {
        parent::query()->where('id', $id)->update($save);
    }
}
