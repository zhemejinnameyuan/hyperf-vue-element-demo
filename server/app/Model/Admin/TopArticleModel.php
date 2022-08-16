<?php


namespace App\Model\Admin;


use App\Model\Model;

/**
 * 内容表
 * Class TopArticleModel
 * @package App\Model\Boss
 */
class TopArticleModel extends Model
{

    /**
     * 表名
     */
    protected $table = 'top_article';

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

    public static function getDataList(array $where = [], int $page, int $limit): array
    {

        $query = parent::query()
            ->join('site_config', function ($join) {
                $join->on('top_article.site_id', '=', 'site_config.id');
            })
            ->where(function ($query) use ($where) {
                //网站ID
                if ($where['site_id']) {
                    $query->where('top_article.site_id', $where['site_id']);
                }
                //网站分类ID
                if ($where['type_id']) {
                    $query->where('site_config.type_id', $where['type_id']);
                }
            });

        $columns = ['site_config.name','top_article.*'];
        return [
            'count' => $query->count(),
            'data' => $query->forPage($page, $limit)->get($columns)->toArray()
        ];
    }

}
