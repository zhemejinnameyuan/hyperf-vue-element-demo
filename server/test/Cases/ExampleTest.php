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
namespace HyperfTest\Cases;

use App\Model\Admin\SysUserModel;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends HttpTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testExample()
    {
        $this->assertTrue(true);

        $where = [
            'username' => 'admin',
            'group_id' => '',
            'status' => -1,
        ];
        $model = container()->get(SysUserModel::class)->getDataList($where, 1, 15);
        $this->assertEquals(1, $model['count']);

        $this->assertTrue(true);

        $res = $this->client->get('/api/demo/aes/delete');
        $this->assertEquals('id 字段是必须的', $res['msg']);
    }
}
