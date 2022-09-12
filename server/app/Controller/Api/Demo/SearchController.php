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
use App\Model\Admin\OpLogModel;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Container\ContainerInterface;

/**
 * 模型全文检索.
 * @AutoController(prefix="api/demo/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Inject
     * @var OpLogModel
     */
    protected $opLogModel;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function search()
    {
        return $this->opLogModel::search('admin')->raw();
    }
}
