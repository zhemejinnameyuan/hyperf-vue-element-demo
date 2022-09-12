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
use Hyperf\DbConnection\Db;

/**
 * 菜单
 * Class SysMenuModel.
 */
class SysMenuModel extends Model
{
    /**
     * 表名.
     */
    protected $table = 'sys_menu';

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
     * 获取菜单.
     */
    public static function getMenuTree(int $userId): array
    {
        if ($userId > 0) {
            $sql = <<<SQL
SELECT
	sys_menu.* 
FROM
	sys_menu
	INNER JOIN sys_user_group ON FIND_IN_SET( sys_menu.id, sys_user_group.rule_ids )
	INNER JOIN sys_user ON sys_user.group_id = sys_user_group.id 
WHERE
	sys_user.id = {$userId} AND sys_menu.status = 1
SQL;

            $dataList = Db::select($sql);
        } else {
            $dataList = parent::query()->where('status', 1)->get()->toArray();
        }

        return self::handelMenuList($dataList, 0);
    }

    /**
     * 获取数据.
     */
    public static function getDataList(array $where = [], int $page, int $limit): array
    {
        $query = parent::query()->where(function ($query) use ($where) {
            if (isset($where['pid'])) {
                $query->where('pid', $where['pid']);
            }
            if (isset($where['status']) && $where['status'] > -1) {
                $query->where('status', $where['status']);
            }
        });

        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->orderBy('id', 'asc')->get(),
        ];
    }

    /**
     * 处理菜单层级.
     */
    protected static function handelMenuList(array $menuList, int $pid): array
    {
        $tree = [];
        foreach ($menuList as $key => $value) {
            if ($value['pid'] == $pid) {
                //拼装meta
                $value['meta'] = [
                    'title' => $value['name'],
                    'icon' => $value['icon'],
                ];
                unset($value['icon']);

                //拼装子级
                $value['children'] = self::handelMenuList($menuList, $value['id']);

                $tree[] = $value;
            }
        }
        return $tree;
    }
}
