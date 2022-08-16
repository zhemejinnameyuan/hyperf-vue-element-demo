<?php


namespace App\Model\Admin;


use App\Model\Model;

/**
 * 配置
 * Class SysUserModel
 * @package App\Model\Boss
 */
class SysConfigModel extends Model
{

    /**
     * 表名
     */
    protected $table = 'sys_config';

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
     * 获取用户数据
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if ($where['key']) {
                $query->where('key', $where['key']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'desc')->get()
        ];
    }

}
