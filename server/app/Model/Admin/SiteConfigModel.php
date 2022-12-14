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
 * 配置
 * Class SiteConfigModel.
 */
class SiteConfigModel extends Model
{
    /**
     * 表名.
     */
    protected $table = 'site_config';

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
     * 获取数据.
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if ($where['name']) {
                $query->where('name', $where['name']);
            }
            if (isset($where['type_id']) && $where['type_id'] > -1) {
                $query->where('type_id', $where['type_id']);
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
     * 获取所有网站数据.
     */
    public static function getAllSite(): array
    {
        $siteList = parent::query()->get()->toArray();
        return $siteList ? array_column($siteList, null, 'id') : [];
    }
}
