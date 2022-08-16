<?php


namespace App\Controller\Api;


use App\Constants\OpBusinessType;
use App\Controller\AbstractController;
use App\Model\Admin\SiteConfigModel;
use App\Model\Admin\SiteTypeModel;
use App\Model\Admin\SysUserModel;
use App\Service\Crontab\TopArticleService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Psr\Container\ContainerInterface;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;

/**
 * 今日热榜相关
 * @AutoController(prefix="api/hotArticle")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class HotArticleController extends AbstractController
{
    /**
     * 操作业务类型
     */
    protected $opBusinessType = OpBusinessType::HOT_ARTICLE;

    /**
     * 各个网站的service
     * @Inject()
     * @var TopArticleService
     */
    protected $topArticleService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }


    /** ================== 站点分类 ================== */

    /**
     * 站点分类-获取分页数据
     */
    public function getSiteTypeDataList(): object
    {
        $where = $this->request->inputs(['name']);
        $dataList = SiteTypeModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 站点分类-删除
     */
    public function siteTypeDelete(): object
    {
        $id = $this->request->input('id');
        if (!$id) {
            return response_error("缺少ID参数");
        }

        $existChild = SiteConfigModel::query()->where('type_id', $id)->count();

        if ($existChild > 0) {
            return response_error("该分类还有网站在使用");
        }

        $result = SiteTypeModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$id, "删除分类");
            return response_success('操作成功');
        } else {
            return response_error("操作失败");
        }
    }

    /**
     * 站点分类-保存
     */
    public function siteTypeSave(): object
    {
        $this->validationCheck(
            [
                'name' => 'required',
            ],
            [
                'name.required' => '请填写分类名称',
            ]
        );

        $saveData = $this->request->inputs(['id', 'name', 'status']);

        if ($saveData['id']) {

            //更新
            $result = SiteTypeModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existName = SiteTypeModel::query()->where('name', $saveData['name'])->count();
            if ($existName) {
                return response_error("分类名称重复,请更换再试");
            }
            $result = SiteTypeModel::insertData($saveData);
        }

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], "添加/更新 分类:" . json_encode($saveData));
            return response_success('操作成功');
        } else {
            return response_error('操作失败');
        }
    }

    /**
     * 获取站点分类select option
     */
    public function siteTypeOptionDataList(): object
    {
        $dataList = SiteTypeModel::query()->get();

        return response_success('success', $dataList);
    }


    /** ================== 网站管理 ================== */

    /**
     *  网站管理-获取分页数据
     */
    public function getSiteConfigDataList(): object
    {
        $where = $this->request->inputs(['name', 'type_id','status']);
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
     * 网站管理-删除
     */
    public function siteConfigDelete(): object
    {
        $id = $this->request->input('id');
        if (!$id) {
            return response_error("缺少ID参数");
        }

        $result = SiteConfigModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$id, "删除分类");
            return response_success('操作成功');
        } else {
            return response_error("操作失败");
        }
    }

    /**
     * 网站管理-保存
     */
    public function siteConfigSave(): object
    {
        $this->validationCheck(
            [
                'name' => 'required',
                'english_name' => 'required',
                'url' => 'required',
                'type_id' => 'required',
            ],
            [
                'name.required' => '请填写网站名称',
                'english_name.required' => '请填写网站英文名称',
                'url.required' => '请填写网站链接',
                'type_id.required' => '请选择网站所属分类',
            ]
        );

        $saveData = $this->request->inputs(['id', 'name', 'english_name', 'url', 'type_id', 'is_login', 'logo', 'login_cookie', 'status']);

        if ($saveData['id']) {
            //更新
            $result = SiteConfigModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $existName = SiteConfigModel::query()->where('name', $saveData['name'])->count();
            if ($existName) {
                return response_error("网站名称重复,请更换再试");
            }
            $result = SiteConfigModel::insertData($saveData);
        }

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], "添加/更新 网站:" . json_encode($saveData));
            return response_success('操作成功');
        } else {
            return response_error('操作失败');
        }
    }

    /**
     * 手动执行
     */
    public function runSite()
    {
        $id = $this->request->input('id');

        $siteInfo = SiteConfigModel::query()->find($id);

        if (!method_exists($this->topArticleService, $siteInfo['english_name'])) {
            return response_error("{$siteInfo['english_name']} not exists ");
        }
        $data = $this->topArticleService->{$siteInfo['english_name']}($siteInfo);


        return response_success("success", $data);
    }
}
