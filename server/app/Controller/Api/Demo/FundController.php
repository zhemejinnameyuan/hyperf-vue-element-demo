<?php

declare(strict_types=1);

namespace App\Controller\Api\Demo;

use App\Amqp\Producer\FundCcmxProducer;
use App\Controller\AbstractController;
use App\Model\Admin\FundCcmxModel;
use App\Model\Admin\FundConfModel;
use App\Model\Admin\SysMenuModel;
use FFI\Exception;
use Hyperf\Amqp\Producer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Phper666\JWTAuth\Middleware\JWTAuthMiddleware;
use Psr\Container\ContainerInterface;
use QL\QueryList;

/**
 * @AutoController(prefix="api/demo/fund")
 * @Middlewares({
 *     @Middleware(JWTAuthMiddleware::class)
 * })
 */
class FundController extends AbstractController
{
    /**
     * @Inject()
     * @var QueryList
     */
    protected $queryList;

    /**
     * @Inject()
     * @var Producer
     */
    protected $producer;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }

    /**
     * 基金-获取分页数据
     * @return object
     */
    public function fundDataList(): object
    {
        $where = $this->request->inputs(['fund', 'stock']);
        $dataList = FundConfModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }

    /**
     * 查询持仓明细
     * @return object
     */
    public function fundCcmx(): object
    {
        $where = $this->request->inputs(['fund_code']);
        $dataList = FundCcmxModel::getDataList($where, $this->getPage(), $this->getLimit());

        return response_success('success', $dataList);
    }


    /**
     * 解析基金配置 并入库
     */
    public function format(): array
    {
        $url = 'http://fund.eastmoney.com/allfund.html';
        $htmlContent = $this->queryList->get($url)->getHtml();
        $titleArr = $htmlContent->find("#code_content .num_right")->texts()->all();

        $title = str_replace(' 基金吧 |', '', $titleArr[0]);
        $title = str_replace('档案', '', $title);

        $arr = explode("|", $title);

        $newArr = [];

        foreach ($arr as $value) {
            $value = trim($value);
            $code = mb_substr($value, 1, 6);
            $name = mb_substr($value, 8);

            if ($code && $name) {
                $newArr[$code] = $name;
                //写入数据库
                $data = [
                    'code' => $code,
                    'name' => $name
                ];
                try {
                    FundConfModel::insertData($data);
                } catch (Exception $exception) {

                }
            }
        }
        return $newArr;
    }

    /**
     * 将基金加入到持仓明细队列
     * @return string
     */
    public function handleCcmx(): string
    {
        //加入队列
        return 'stop';
        $conf = FundConfModel::query()->select()->get();

        foreach ($conf as $value) {
            $data = ['code' => $value['code']];
            $message = new FundCcmxProducer($data);
            $this->producer->produce($message);
        }

        return 'success';
    }
}
