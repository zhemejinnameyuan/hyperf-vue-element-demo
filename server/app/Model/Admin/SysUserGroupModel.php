<?php


namespace App\Model\Admin;


use App\Model\Model;

/**
 * 用户权限组
 * Class SysUserModel
 * @package App\Model\Boss
 */
class SysUserGroupModel extends Model
{

    /**
     * 表名
     */
    protected $table = 'sys_user_group';

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
     * 获取数据
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {

            if (isset($where['status']) && $where['status'] > -1) {
                $query->where('status', $where['status']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'asc')->get()
        ];
    }

}
