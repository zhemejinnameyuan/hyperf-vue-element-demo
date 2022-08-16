<?php

declare(strict_types=1);

namespace App\Controller\Api\Demo;

use App\Controller\AbstractController;
use App\Model\Admin\OpLogModel;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Container\ContainerInterface;

/**
 * 模型全文检索
 * @AutoController(prefix="api/demo/search")
 */
class SearchController extends AbstractController
{

    /**
     * @Inject()
     * @var OpLogModel
     */
    protected $opLogModel;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

    }


    public function search(){
        $orders = $this->opLogModel::search('admin')->raw();

        return $orders;
    }
}
