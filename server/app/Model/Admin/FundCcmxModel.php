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
 * 基金持仓明细配置表
 * Class FundConfModel.
 */
class FundCcmxModel extends Model
{
    /**
     * 表名.
     */
    protected $table = 'fund_ccmx';

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
            if ($where['fund_code']) {
                $query->where('fund_code', $where['fund_code']);
            }
        });
        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->get(),
        ];
    }
}
