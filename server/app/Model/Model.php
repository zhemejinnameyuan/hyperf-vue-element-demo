<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;

    /**
     * 封装-插入数据
     * @param $data
     * @return int
     */
    public static function insertData($data): int
    {
        $data['created_at'] = time2date(time());
        $data['updated_at'] = time2date(time());

        return self::query(true)->insertGetId($data);
    }

    /**
     * 封装-更新数据
     * @param array $id 主键id
     * @param array $data 更新的数据
     * @return int
     */
    public static function updateData($id, $data): int
    {
        return self::query(true)->where('id', $id)->update($data);
    }
}
