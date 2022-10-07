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

namespace App\Controller\Api\Demo;

use App\Controller\AbstractController;
use Faker\Factory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @Controller(prefix="api/demo/elasticsearch")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class ElasticsearchController extends AbstractController
{
    /**
     * @var ClientBuilderFactory
     * @Inject
     */
    protected $esBuilder;

    /**
     * @var \Elasticsearch\ClientBuilder
     */
    protected $esClient;

    /**
     * @Inject()
     * @var Factory
     */
    protected $fakerFactory;
    protected $fakerClass;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->fakerClass = $this->fakerFactory->create('zh_CN');
        $this->esClient = $this->esBuilder->create()->setHosts(['http://127.0.0.1:9200'])->build();
    }

    /**
     * 状态
     * @RequestMapping(path="index", methods="GET")
     */
    public function info(): array
    {
        return $this->esClient->info();
    }

    /**
     * 插入数据.
     * @RequestMapping(path="insertData", methods="POST")
     */
    public function insertData()
    {
        $addr = '上海市xxx';
        $value = '外滩';

        $params = [
            'index' => 'demo',
            'type' => 'demo_address',
            'body' => [
                'address' => $addr,
                'value' => $value,
            ],
        ];

        return $this->esClient->index($params);
    }

    /**
     * 删除数据.
     * @RequestMapping(path="deleteData", methods="POST")
     */
    public function deleteData()
    {

        $params = [
            'index' => 'demo',
            'type' => 'demo_address',
            'id' => '9'
        ];

        dump($this->fakerClass->address);
        return $this->fakerClass->address;

        return $this->esClient->delete($params);
    }

    /**
     * 创建索引.
     * @RequestMapping(path="create", methods="POST")
     */
    public function create()
    {
        $arr = range(1, 100);

        $body = [];
        foreach ($arr as $key => $value) {
            $body[] = [
                'index' => [
                    '_index'=>'demo',
                    '_type'=>'demo_address'
                ],
                'value' => $this->fakerClass->company,
                'address' => $this->fakerClass->address
            ];
        }

        $params = [
            'body' => $body,
        ];

        return $this->esClient->bulk($params);
    }

    /**
     * 查询.
     * @RequestMapping(path="suggestions", methods="GET")
     */
    public function suggestions()
    {

        $keyWord = $this->request->input('keyword', '金沙');

        if (!$keyWord) {
            return response_error('请输入关键词');
        }
        //多个字段查询
        $params['index'] = 'demo';
        $params['type'] = 'demo_address';
        $params['size'] = '100';
        $params['from'] = '0';
        //查询字段
        $params['body']['query']['bool']['should'] = [
            ['match' => ['value' => $keyWord]],
            ['match' => ['address' => $keyWord]],
        ];
        //匹配度
        $params['body']['query']['bool']['minimum_should_match'] = '99%';

        //高亮显示
        $params['body']['highlight']['fields'] = [
            'value' => [
                'pre_tags' => '<em>',
                'post_tags' => '</em>',
            ],
            'address' => [
                'pre_tags' => '<em>',
                'post_tags' => '</em>',
            ],
        ];

        //按打分结果排序
        $params['body']['sort'] = [
            ['_score' => ['order' => 'desc']],
        ];

        $data = $this->esClient->search($params);

        $data = $data['hits']['hits'];

        $returnData = [];
        if ($data) {
            $returnData = array_column($data, '_source'); //非高亮
//            //高亮
//            $returnData =  array_column($data, 'highlight');
        }

        return response_success('success', $returnData);
    }

    /**
     * 删除索引.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function delete()
    {
        $params = [
            'index' => 'demo',
        ];

        return $this->esClient->indices()->delete($params);
    }
}
