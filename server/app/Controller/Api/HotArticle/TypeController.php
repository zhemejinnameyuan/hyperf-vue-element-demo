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
use App\Request\HotArticle\TypeRequest;
use App\Service\Crontab\TopArticleService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;

/**
 * 今日热榜相关.
 * @Controller(prefix="api/hotArticle/type")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class TypeController extends AbstractController
{
    /**
     * 操作业务类型.
     */
    protected $opBusinessType = OpBusinessType::HOT_ARTICLE;

    /**
     * @Inject()
     * @var TypeRequest
     */
    public $typeRequest;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }


    /**
     * 站点分类-获取分页数据.
     * @RequestMapping(path="",methods="GET")
     */
    public function getType(): object
    {
        $where = $this->request->inputs(['name']);
        $dataList = SiteTypeModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 站点分类-删除.
     * @RequestMapping(path="",methods="DELETE")
     */
    public function deleteType(): object
    {
        $id = $this->request->input('id');
        $this->typeRequest->scene('delete')->validateResolved();

        $existChild = SiteConfigModel::query()->where('type_id', $id)->count();

        if ($existChild > 0) {
            return response_error('该分类还有网站在使用');
        }

        $result = SiteTypeModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$id, '删除分类');
            return response_success();
        }
        return response_error();
    }

    /**
     * 站点分类-保存.
     * @RequestMapping(path="",methods="POST")
     */
    public function addType(): object
    {
        $this->typeRequest->scene("add")->validateResolved();

        $saveData = $this->request->inputs(['name', 'status']);

        $existName = SiteTypeModel::query()->where('name', $saveData['name'])->count();
        if ($existName) {
            return response_error('分类名称重复,请更换再试');
        }
        $result = SiteTypeModel::insertData($saveData);
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], '新增分类:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 站点分类-保存.
     * @RequestMapping(path="",methods="PUT")
     */
    public function updateType(): object
    {
        $this->typeRequest->scene("update")->validateResolved();

        $saveData = $this->request->inputs(['id', 'name', 'status']);

        $result = SiteTypeModel::updateData($saveData['id'], $saveData);
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], '更新分类:' . json_encode($saveData));
            return response_success();
        }
        return response_error();
    }

    /**
     * 获取站点分类select option.
     * @RequestMapping(path="optionList",methods="GET")
     */
    public function optionList(): object
    {
        $dataList = SiteTypeModel::query()->get();

        return response_success('success', $dataList);
    }

}
