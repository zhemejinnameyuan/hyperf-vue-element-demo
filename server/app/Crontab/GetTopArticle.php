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
namespace App\Crontab;

use App\Model\Admin\SiteConfigModel;
use App\Model\Admin\TopArticleModel;
use App\Service\Crontab\TopArticleService;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * @Crontab(name="采集top排行榜", rule="* * * * * *", callback="index", memo="getTopArticle")
 * Class GetTopArticle
 */
class GetTopArticle
{
    /**
     * 各个网站的service.
     * @Inject
     * @var TopArticleService
     */
    protected $topArticleService;

    public function __construct(ContainerInterface $container)
    {
    }

    /**
     * 入口.
     */
    public function index(): void
    {
        $siteConf = SiteConfigModel::getAllSite();
        foreach ($siteConf as $siteInfo) {
            if ($siteInfo['status'] == 1) {
                go(function () use ($siteInfo) {
                    $this->execute($siteInfo);
                });
            }
        }
    }

    /**
     * 执行入口.
     * @param $siteInfo
     */
    public function execute($siteInfo): void
    {
        if (! method_exists($this->topArticleService, $siteInfo['english_name'])) {
            logger('crontab.GetTopArticle.index')->error("{$siteInfo['english_name']} not exists ");
        }
        $data = $this->topArticleService->{$siteInfo['english_name']}($siteInfo);

        if ($data) {
            foreach ($data as $title => $url) {
                $saveData = [
                    'title' => $title,
                    'url' => $url,
                    'site_id' => $siteInfo['id'],
                ];

                try {
                    TopArticleModel::insertData($saveData);
                } catch (\Exception $exception) {
                    dump($exception->getMessage());
                }
            }
        }
    }
}
