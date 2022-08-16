<?php


namespace App\Model\Admin;


use App\Constants\UserCode;
use App\Model\Model;

/**
 * 站点-分类
 * Class SysUserModel
 * @package App\Model\Boss
 */
class SiteTypeModel extends Model
{

    /**
     * 表名
     */
    protected $table = 'site_type';

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
     * 获取数据列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if ($where['name']) {
                $query->where('name', $where['name']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'desc')->get()
        ];
    }


    /**
     * 更新用户信息
     * @param int $id
     * @param array $save
     */
    protected static function updateInfo(int $id, array $save): void
    {
        parent::query()->where('id', $id)->update($save);
    }


}
