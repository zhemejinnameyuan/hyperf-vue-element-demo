<?php


namespace App\Controller\Api;


use App\Constants\OpBusinessType;
use App\Controller\AbstractController;
use App\Model\Admin\SysConfigModel;
use App\Model\Admin\SysMenuModel;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Psr\Container\ContainerInterface;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;

/**
 * @AutoController(prefix="api/system")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class SystemController extends AbstractController
{
    /**
     * 操作业务类型
     */
    protected $opBusinessType = OpBusinessType::SYSTEM;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }

    /** ================== 菜单管理 ================== */

    /**
     * 菜单-获取分页数据
     */
    public function menuDataList(): object
    {
        $where = $this->request->inputs(['pid', 'status']);
        $dataList = SysMenuModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 菜单-保存
     */
    public function menuSave(): object
    {
        $this->validationCheck(
            [
                'name' => 'required',
                'path' => 'required',
                'component' => 'required',
            ],
            [
                'name.required' => '菜单名称不能为空',
                'path.required' => '路由路径不能为空',
                'component.required' => '视图组件不能为空',
            ]
        );

        $saveData = $this->request->inputs(['component', 'redirect', 'icon', 'id', 'name', 'path', 'pid', 'status']);

        if ($saveData['id']) {
            //更新
            $result = SysMenuModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $result = SysMenuModel::insertData($saveData);
        }

        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], "添加/更新 菜单:" . json_encode($saveData));
            return response_success('操作成功');
        } else {
            return response_error('操作失败');
        }

    }

    /**
     * 菜单-删除
     */
    public function menuDelete(): object
    {
        $id = $this->request->input('id');
        if (!$id) {
            return response_error("缺少ID参数");
        }

        //查看是否有下级
        $isChildren = SysMenuModel::query()->where('pid', $id)->count();
        if ($isChildren) {
            return response_error("该菜单还有下级", $isChildren);
        }

        $result = SysMenuModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, $id, "删除菜单");
            return response_success('操作成功');
        } else {
            return response_error("操作失败");
        }
    }

    /** ================== 配置管理 ================== */


    /**
     * 配置-获取分页数据
     */
    public function configDataList(): object
    {
        $where = $this->request->inputs(['key']);
        $dataList = SysConfigModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 配置-保存
     */
    public function configSave(): object
    {
        $this->validationCheck(
            [
                'key' => 'required',
                'value' => 'required',
                'desc' => 'required',
                'type' => 'required',
            ],
            [
                'key.required' => '配置项不能为空',
                'value.required' => '值不能为空',
                'desc.required' => '描述不能为空',
                'type.required' => '类型不能为空',
            ]
        );

        $saveData = $this->request->inputs(['key', 'value', 'desc', 'id', 'type', 'status']);

        if ($saveData['type'] == 'json' && !json_decode($saveData['value'], true)) {
            return response_error("请填写正确的json格式");
        }

        if ($saveData['id']) {
            //更新
            $result = SysConfigModel::updateData($saveData['id'], $saveData);
        } else {
            //新增
            $result = SysConfigModel::insertData($saveData);
        }

        if ($result !== false) {
            //重置配置
            parent::initConfig();
            $this->addOpLog($this->opBusinessType, (int)$saveData['id'], "添加/更新 配置:" . json_encode($saveData));
            return response_success('操作成功');
        } else {
            return response_error('操作失败');
        }

    }

    /**
     * 配置-删除
     */
    public function configDelete(): object
    {
        $id = $this->request->input('id');
        if (!$id) {
            return response_error("缺少ID参数");
        }


        $result = SysConfigModel::query()->where('id', $id)->delete();
        if ($result !== false) {
            $this->addOpLog($this->opBusinessType, $id, "删除配置");
            return response_success('操作成功');
        } else {
            return response_error("操作失败");
        }
    }
}
