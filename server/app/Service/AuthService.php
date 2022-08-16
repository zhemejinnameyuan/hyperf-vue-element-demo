<?php


namespace App\Service;


use Hyperf\DbConnection\Db;

class AuthService
{
    /**
     * 白名单url
     */
    protected $whiteUrl = [
        '/boss/index',
        '/boss/index/info',
        '/boss/authManager/editPassword',
    ];

    /**
     * 获取用户的所有权限节点
     * @param int $userId 用户id
     * @return array
     */
    public function getAllNode(int $userId): array
    {
        $sql = <<<sql
SELECT
	lower(url) as url 
FROM
	boss_auth_node
	INNER JOIN (
SELECT
	GROUP_CONCAT( rules SEPARATOR ',' ) AS rules 
FROM
	boss_auth_group
	INNER JOIN boss_user ON boss_user.id = '$userId' 
	AND FIND_IN_SET( boss_auth_group.id, boss_user.group_ids ) 
	) rules_tmp ON FIND_IN_SET( boss_auth_node.id, rules_tmp.rules )
sql;

        $allNode = Db::select($sql);

        if ($allNode) {
            return array_merge(
                array_column($allNode, 'url'),
                $this->whiteUrl
            );
        } else {
            return $this->whiteUrl;
        }

    }
}