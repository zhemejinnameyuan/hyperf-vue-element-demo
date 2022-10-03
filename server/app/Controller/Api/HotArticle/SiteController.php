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
namespace App\Controller\Api\HotArticle;

use App\Constants\OpBusinessType;
use App\Controller\AbstractController;
use App\Model\Admin\SiteConfigModel;
use App\Model\Admin\SiteTypeModel;
use App\Request\HotArticle\SiteRequest;
use App\Service\Crontab\TopArticleService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * 今日热榜相关.
 * @Controller(prefix="api/hotArticle/site")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class SiteController extends AbstractController
{
    /**
     * @Inject
     * @var SiteRequest
     */
    public $siteRequest;

    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::HOT_ARTICLE;

    /**
     * 各个网站的service.
     * @Inject
     * @var TopArticleService
     */
    protected $topArticleService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     *  网站管理-获取分页数据.
     * @RequestMapping(path="", methods="GET")
     */
    public function getSite(): object
    {
        $where = $this->request->inputs(['name', 'type_id', 'status']);
        $dataList = SiteConfigModel::getDataList($where, $this->getPage(), $this->getLimit());

        if ($dataList['data']) {
            $siteType = SiteTypeModel::query()->get()->toArray();
            $siteType = array_column($siteType, 'name', 'id');

            foreach ($dataList['data'] as &$item) {
                $item['type_name'] = $siteType[$item['type_id']];
            }
        }

        return response_success('success', $dataList);
    }

    /**
     * 网站管理-添加.
     * @RequestMapping(path="", methods="POST")
     */
    public function addSite(): object
    {
        $this->siteRequest->scene('add')->validateResolved();

        $saveData = $this->request->inputs(['id', 'name', 'english_name', 'url', 'type_id', 'is_login', 'logo', 'login_cookie', 'status']);

        $existName = SiteConfigModel::query()->where('name', $saveData['name'])->count();
        if ($existName) {
            return response_error('网站名称重复,请更换再试');
        }
        $result = SiteConfigModel::insertData($saveData);

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '新增网站:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 网站管理-更新.
     * @RequestMapping(path="", methods="PUT")
     */
    public function updateSite(): object
    {
        $this->siteRequest->scene('update')->validateResolved();

        $saveData = $this->request->inputs(['id', 'name', 'english_name', 'url', 'type_id', 'is_login', 'logo', 'login_cookie', 'status']);

        $result = SiteConfigModel::updateData($saveData['id'], $saveData);

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $saveData['id'], '更新网站:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 网站管理-删除.
     * @RequestMapping(path="", methods="DELETE")
     */
    public function deleteSite(): object
    {
        $id = $this->request->input('id');
        $this->siteRequest->scene('delete')->validateResolved();

        $result = SiteConfigModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int) $id, '删除网站');
            return response_success();
        }
        return response_error();
    }

    /**
     * 手动执行.
     * @RequestMapping(path="runSite", methods="POST")
     */
    public function runSite()
    {
        $id = $this->request->input('id');

        $siteInfo = SiteConfigModel::query()->find($id);

        if (! method_exists($this->topArticleService, $siteInfo['english_name'])) {
            return response_error("{$siteInfo['english_name']} not exists ");
        }
        $data = $this->topArticleService->{$siteInfo['english_name']}($siteInfo);

        return response_success('success', $data);
    }
}
