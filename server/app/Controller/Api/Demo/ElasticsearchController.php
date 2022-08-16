<?php

declare(strict_types=1);

namespace App\Controller\Api\Demo;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * @AutoController(prefix="api/demo/elasticsearch")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class ElasticsearchController extends AbstractController
{
    /**
     * @var ClientBuilderFactory
     * @Inject()
     */
    protected $esBuilder;
    /**
     * @var \Elasticsearch\ClientBuilder
     */
    protected $esClient;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->esClient =  $this->esBuilder->create()->setHosts(['http://127.0.0.1:9200'])->build();
    }

    /**
     * 状态
     * @return array
     */
    public function info(): array
    {
        return $this->esClient->info();
    }

    /**
     * 创建索引
     */
    public function create()
    {

        $oriData = '[{ "value": "三全鲜食（北新泾店）", "address": "长宁区新渔路144号" },
                    { "value": "Hot honey 首尔炸鸡（仙霞路）", "address": "上海市长宁区淞虹路661号" },
                    { "value": "新旺角茶餐厅", "address": "上海市普陀区真北路988号创邑金沙谷6号楼113" },
                    { "value": "泷千家(天山西路店)", "address": "天山西路438号" },
                    { "value": "胖仙女纸杯蛋糕（上海凌空店）", "address": "上海市长宁区金钟路968号1幢18号楼一层商铺18-101" },
                    { "value": "贡茶", "address": "上海市长宁区金钟路633号" },
                    { "value": "豪大大香鸡排超级奶爸", "address": "上海市嘉定区曹安公路曹安路1685号" },
                    { "value": "茶芝兰（奶茶，手抓饼）", "address": "上海市普陀区同普路1435号" },
                    { "value": "十二泷町", "address": "上海市北翟路1444弄81号B幢-107" },
                    { "value": "星移浓缩咖啡", "address": "上海市嘉定区新郁路817号" },
                    { "value": "阿姨奶茶/豪大大", "address": "嘉定区曹安路1611号" },
                    { "value": "新麦甜四季甜品炸鸡", "address": "嘉定区曹安公路2383弄55号" },
                    { "value": "Monica摩托主题咖啡店", "address": "嘉定区江桥镇曹安公路2409号1F，2383弄62号1F" },
                    { "value": "浮生若茶（凌空soho店）", "address": "上海长宁区金钟路968号9号楼地下一层" },
                    { "value": "NONO JUICE  鲜榨果汁", "address": "上海市长宁区天山西路119号" },
                    { "value": "CoCo都可(北新泾店）", "address": "上海市长宁区仙霞西路" },
                    { "value": "快乐柠檬（神州智慧店）", "address": "上海市长宁区天山西路567号1层R117号店铺" },
                    { "value": "Merci Paul cafe", "address": "上海市普陀区光复西路丹巴路28弄6号楼819" },
                    { "value": "猫山王（西郊百联店）", "address": "上海市长宁区仙霞西路88号第一层G05-F01-1-306" },
                    { "value": "枪会山", "address": "上海市普陀区棕榈路" },
                    { "value": "纵食", "address": "元丰天山花园(东门) 双流路267号" },
                    { "value": "钱记", "address": "上海市长宁区天山西路" },
                    { "value": "壹杯加", "address": "上海市长宁区通协路" },
                    { "value": "唦哇嘀咖", "address": "上海市长宁区新泾镇金钟路999号2幢（B幢）第01层第1-02A单元" },
                    { "value": "爱茜茜里(西郊百联)", "address": "长宁区仙霞西路88号1305室" },
                    { "value": "爱茜茜里(近铁广场)", "address": "上海市普陀区真北路818号近铁城市广场北区地下二楼N-B2-O2-C商铺" },
                    { "value": "鲜果榨汁（金沙江路和美广店）", "address": "普陀区金沙江路2239号金沙和美广场B1-10-6" },
                    { "value": "开心丽果（缤谷店）", "address": "上海市长宁区威宁路天山路341号" },
                    { "value": "超级鸡车（丰庄路店）", "address": "上海市嘉定区丰庄路240号" },
                    { "value": "妙生活果园（北新泾店）", "address": "长宁区新渔路144号" },
                    { "value": "香宜度麻辣香锅", "address": "长宁区淞虹路148号" },
                    { "value": "凡仔汉堡（老真北路店）", "address": "上海市普陀区老真北路160号" },
                    { "value": "港式小铺", "address": "上海市长宁区金钟路968号15楼15-105室" },
                    { "value": "蜀香源麻辣香锅（剑河路店）", "address": "剑河路443-1" },
                    { "value": "北京饺子馆", "address": "长宁区北新泾街道天山西路490-1号" },
                    { "value": "饭典*新简餐（凌空SOHO店）", "address": "上海市长宁区金钟路968号9号楼地下一层9-83室" },
                    { "value": "焦耳·川式快餐（金钟路店）", "address": "上海市金钟路633号地下一层甲部" },
                    { "value": "动力鸡车", "address": "长宁区仙霞西路299弄3号101B" },
                    { "value": "浏阳蒸菜", "address": "天山西路430号" },
                    { "value": "四海游龙（天山西路店）", "address": "上海市长宁区天山西路" },
                    { "value": "樱花食堂（凌空店）", "address": "上海市长宁区金钟路968号15楼15-105室" },
                    { "value": "壹分米客家传统调制米粉(天山店)", "address": "天山西路428号" },
                    { "value": "福荣祥烧腊（平溪路店）", "address": "上海市长宁区协和路福泉路255弄57-73号" },
                    { "value": "速记黄焖鸡米饭", "address": "上海市长宁区北新泾街道金钟路180号1层01号摊位" },
                    { "value": "红辣椒麻辣烫", "address": "上海市长宁区天山西路492号" },
                    { "value": "(小杨生煎)西郊百联餐厅", "address": "长宁区仙霞西路88号百联2楼" },
                    { "value": "阳阳麻辣烫", "address": "天山西路389号" },
                    { "value": "南拳妈妈龙虾盖浇饭", "address": "普陀区金沙江路1699号鑫乐惠美食广场A13" }]';
        $oriData = json_decode($oriData, true);


        foreach ($oriData as $key => $value) {
            $params = [
                'index' => 'demo',
                'type' => 'demo_address',
                'id' => $key,
                'body' => $value
            ];

            $res = $this->esClient->create($params);
        }

    }

    public function suggestions()
    {

//        //单个字段查询
//        $params = [
//            'index' => 'video',
//            'type' => 'video_info',
//            'size' => '100',
//            'from' => '0',
//            'body' => [
//                'query' => [
//                    'match' => [
//                        'title' => [
//                            'query' => '周星驰',
//                            'minimum_should_match' => '100%'
//                        ]
//                    ]
//                ]
//            ]
//        ];

        $keyWord = $this->request->input('keyword','金沙');

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
        $params['body']['query']['bool']['minimum_should_match'] = "80%";

        //高亮显示
        $params['body']['highlight']['fields'] = [
            'value' => [
                'pre_tags' => "<em>",
                'post_tags' => "</em>",
            ],
            'address' => [
                'pre_tags' => "<em>",
                'post_tags' => "</em>",
            ]
        ];

        //按打分结果排序
        $params['body']['sort'] = [
            ['_score' => ['order' => 'desc']]
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
     * 删除索引
     */
    public function delete()
    {
        $params = [
            'index' => 'demo',
        ];

        $res = $this->esClient->indices()->delete($params);

        return $res;
    }
}
