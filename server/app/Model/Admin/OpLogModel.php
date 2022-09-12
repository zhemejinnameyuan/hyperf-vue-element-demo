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
use Hyperf\Scout\Searchable;

/**
 * 操作日志
 * Class SysUserModel.
 */
class OpLogModel extends Model
{
    use Searchable;

    /**
     * 表名.
     */
    protected $table = 'op_log';

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

    public function searchableAs()
    {
        return 'posts_index';
    }

    /**
     * 获取用户数据.
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if ($where['op_username']) {
                $query->where('op_username', $where['op_username']);
            }
            if (isset($where['business_type']) && $where['business_type'] > -1) {
                $query->where('business_type', $where['business_type']);
            }
            if (isset($where['content']) && $where['content']) {
                $query->where('content', 'like', "%{$where['content']}%");
            }
            if ($where['date']) {
                $query->whereBetween('created_at', $where['date']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'desc')->get(),
        ];
    }
}
