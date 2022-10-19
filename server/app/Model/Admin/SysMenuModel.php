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
     * @param int $userId
     * @param int $type 0-所有，1-菜单，2-按钮
     * @return array
     */
    public static function getMenuTree(int $userId, int $type = 0): array
    {
        if ($userId > 0) {
            $where = '';
            if ($type) {
                $where .= "and sys_menu.type='{$type}'";
            }
            $sql = <<<SQL
SELECT
	sys_menu.* 
FROM
	sys_menu
	INNER JOIN sys_user_group ON FIND_IN_SET( sys_menu.id, sys_user_group.menu_ids )
	INNER JOIN sys_user ON sys_user.group_id = sys_user_group.id 
WHERE
	sys_user.id = {$userId} AND sys_menu.status = 1
	{$where}
ORDER BY sort asc 	
SQL;

            $dataList = Db::select($sql);
        } else {
            $dataList = parent::query()->where('status', 1)->orderBy('sort')->get()->toArray();
        }

        if ($type == 2) {
            //按钮直接返回，不用处理菜单逻辑
            return $dataList;
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
            'data' => $query->forPage($page, $limit)->orderBy('sort', 'asc')->get(),
        ];
    }

    /**
     * 获取分组对应的api path.
     * @param $ids
     * @return array
     */
    public static function getApiPath($ids)
    {
        $ids = is_string($ids) ? explode(',', $ids) : $ids;
        $query = parent::query()->whereIn('id', $ids)->get();

        $apiPath = [];
        if ($query) {
            foreach ($query as $item) {
                $tmpApiPathArr = $item['api_path'] ? explode("\n", $item['api_path']) : [];
                foreach ($tmpApiPathArr as $tmpUrl) {
                    if ($tmpUrl) {
                        $tmpUrl = strtolower($tmpUrl);
                        $apiPath[$tmpUrl] = $tmpUrl;
                    }
                }
            }
        }

        return $apiPath;
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
