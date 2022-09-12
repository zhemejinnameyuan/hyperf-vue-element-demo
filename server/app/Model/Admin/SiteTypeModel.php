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

use App\Model\Model;

/**
 * 站点-分类
 * Class SysUserModel.
 */
class SiteTypeModel extends Model
{
    /**
     * 表名.
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
     * 获取数据列表.
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
            'data' => $query->forPage($page, $limit)->orderBy('id', 'desc')->get(),
        ];
    }

    /**
     * 更新用户信息.
     */
    protected static function updateInfo(int $id, array $save): void
    {
        parent::query()->where('id', $id)->update($save);
    }
}
