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
namespace App\Service\Crontab;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use QL\QueryList;

/**
 * Class TopArticleService.
 */
class TopArticleService
{
    /**
     * http客户端.
     * @Inject
     * @var ClientFactory
     */
    protected $guzzleHttpClient;

    /**
     * queryList 类.
     * @Inject
     * @var QueryList
     */
    protected $queryList;

    /**
     * 百度.
     * @param array $siteInfo
     */
    public function baidu($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.baidu')->info('start');
            $htmlContent = $this->queryList->get('http://news.baidu.com');

            $titleArr = $htmlContent->find('#pane-news li a')->texts()->all();
            $hrefArr = $htmlContent->find('#pane-news li a')->attrs('href')->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.baidu')->error($exception->getMessage());
            $topList = [];
        }

        return $topList;
    }

    /**
     * v2ex.
     * @param array $siteInfo
     */
    public function v2ex($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.v2ex')->info('start');
            $htmlContent = $this->queryList->get('https://www.v2ex.com/?tab=hot');

            $titleArr = $htmlContent->find('.box .item .item_title a')->texts()->all();
            $hrefArr = $htmlContent->find('.box .item .item_title a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                //去掉url#reply
                preg_match_all('/^(.*?)#reply\d+/', $path, $matchs);
                return 'https://www.v2ex.com' . $matchs[1][0];
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.v2ex')->error($exception->getMessage());
            $topList = [];
        }

        return $topList;
    }

    /**
     * 网易.
     * @param array $siteInfo
     */
    public function netEasy($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.netEasy')->info('start');
            $options = [
                'base_uri' => 'https://news.163.com',
                'referer' => true,
                'http_errors' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                ],
            ];

            $content = $this->guzzleHttpClient->create($options)->get('/rank')->getBody();

            //编码转换
            $content = iconv('GBK//IGNORE', 'UTF-8', $content);

            $hrefArr = $this->queryList->html($content)->find('table')->eq(0)->find('a')->attrs('href')->all();
            $titleArr = $this->queryList->html($content)->find('table')->eq(0)->find('a')->texts()->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.netEasy')->error($exception->getMessage());
            $topList = [];
        }
        return $topList;
    }

    /**
     * 微博热搜.
     * @param array $siteInfo
     */
    public function weibo($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.weibo')->info('start');
            $htmlContent = $this->queryList->get('https://s.weibo.com/top/summary');

            $titleArr = $htmlContent->find('#pl_top_realtimehot table a')->texts()->all();
            $hrefArr = $htmlContent->find('#pl_top_realtimehot table a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://s.weibo.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.weibo')->error($exception->getMessage());
            $topList = [];
        }

        return $topList;
    }

    /**
     * it之家.
     * @param array $siteInfo
     */
    public function itHome($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.itHome')->info('start');
            $htmlContent = $this->queryList->get('https://www.ithome.com/blog/');

            $titleArr = $htmlContent->find('.bl li .c h2 a')->texts()->all();
            $hrefArr = $htmlContent->find('.bl li .c h2 a')->attrs('href')->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.itHome')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 知乎.
     * @param array $siteInfo
     */
    public function zhihu($siteInfo = []): array
    {
        try {
            $cookie = '_zap=f0e5c325-63a6-4f64-9964-a6e08ba35ea6; d_c0="AODnu7ofdA-PThC4t5A6dVg4Ws70Qb92pqo=|1558270983"; __gads=ID=22541f247e27c6b6:T=1558444838:S=ALNI_MbwjnMqNiRULqpil1KfcGh32_Dvmg; __utmv=51854390.000--|3=entry_date=20190519=1; _ga=GA1.2.1816932802.1563285349; __utma=51854390.1816932802.1563285349.1563285349.1567520404.2; q_c1=89b7754ceafa4314a8aaed7173d1726d|1582193662000|1558270985000; _xsrf=4653cc58-8fd9-4769-ab26-79c5253257d6; _gid=GA1.2.91324763.1600057796; Hm_lvt_98beee57fd2ef70ccdd5ca52b9740c49=1600068561,1600074295,1600091302,1600141413; SESSIONID=KEHGGui7ClbZtPdklxFDAedPs3Mns6OJ7wQ80eJRSog; capsion_ticket="2|1:0|10:1600141412|14:capsion_ticket|44:ODE0Y2NmOTdiNmMwNGNiYThkY2NiOTI2Y2IwNWE1MzU=|6541fb4fdacd1d5eae77c3098bda1ff0b4d97f5ae3c43c6806761428edfb101d"; JOID=VFoQAE1M05Ho4z7WcUkHBPlwe65jA5HfqqRPuEAPu_S0km62HSvq8rLnPddyiCX2X370FELMaTSWlJ5wGsDnUqw=; osd=U1oVBElL05Ts5znWdE0DA_l1f6pkA5TbrqNPvUQLvPSxlmqxHS7u9rXnONN2jyXzW3rzFEfIbTOWkZp0HcDiVqg=; z_c0="2|1:0|10:1600141487|4:z_c0|92:Mi4xd2V3V0VnQUFBQUFBNE9lN3VoOTBEeVlBQUFCZ0FsVk5yNFpOWUFBX2Q1bVJCbXFTS0xZVUYtNWlRTG1fVzEzT3RR|40be796862d4fd8fae60a24455efcdf597f83b9de3fda77d459d9648ae846fee"; unlock_ticket="APDjJau7_g8mAAAAYAJVTbc_YF-skR88QE1gy4JDbZE4yXMzm9i3jg=="; Hm_lpvt_98beee57fd2ef70ccdd5ca52b9740c49=1600142072; tshl=; tst=h; KLBRSID=57358d62405ef24305120316801fd92a|1600142214|1600141407';

            $htmlContent = $this->queryList->get(
                'https://www.zhihu.com/hot',
                [],
                [
                    'headers' => [
                        'Cookie' => $cookie,
                    ],
                ]
            );

            $titleArr = $htmlContent->find('.HotItem-content a')->attrs('title')->all();
            $hrefArr = $htmlContent->find('.HotItem-content a')->attrs('href')->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            $topList = [];
        }

        return $topList;
    }

    /**
     * 36kr.
     * @param array $siteInfo
     */
    public function sanliukr($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.36kr')->info('start');
            $htmlContent = $this->queryList->get('https://36kr.com/hot-list/catalog');

            $titleArr = $htmlContent->find('.article-list .title-wrapper a')->texts()->all();
            $hrefArr = $htmlContent->find('.article-list .title-wrapper a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://36kr.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.36kr')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 豆瓣.
     * @param array $siteInfo
     */
    public function douban($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.douban')->info('start');
            $htmlContent = $this->queryList->get('https://www.douban.com/group/explore');

            $titleArr = $htmlContent->find('.article .channel-item a')->texts()->all();
            $hrefArr = $htmlContent->find('.article .channel-item a')->attrs('href')->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.douban')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 天涯.
     * @param array $siteInfo
     */
    public function tianya($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.tianya')->info('start');
            $htmlContent = $this->queryList->get('http://bbs.tianya.cn/list.jsp?item=funinfo&grade=3&order=1');

            $titleArr = $htmlContent->find('.mt5 .td-title a')->texts()->all();
            $hrefArr = $htmlContent->find('.mt5 .td-title a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'http://bbs.tianya.cn' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.tianya')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 微信
     * @param array $siteInfo
     */
    public function weixin($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.weixin')->info('start');
            $htmlContent = $this->queryList->get('https://weixin.sogou.com/?pid=sogou-wsse-721e049e9903c3a7&kw=');

            $titleArr = $htmlContent->find('.news-list li h3 a')->texts()->all();
            $hrefArr = $htmlContent->find('.news-list li h3 a')->attrs('href')->all();

            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.weixin')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * csdn.
     * @param array $siteInfo
     */
    public function csdn($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.csdn')->info('start');
            $htmlContent = $this->queryList->get('https://blog.csdn.net/phoenix/web/blog/hotRank?page=0&pageSize=25&child_channel=')->getHtml();

            $content = json_decode($htmlContent, true)['data'];

            $titleArr = array_column($content, 'articleTitle');
            $hrefArr = array_column($content, 'articleDetailUrl');
            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.csdn')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * segmentfault.
     * @param array $siteInfo
     */
    public function segmentfault($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.segmentfault')->info('start');
            $htmlContent = $this->queryList->get('https://segmentfault.com/hottest');

            $titleArr = $htmlContent->find('.news-list .news__item-title')->texts()->all();
            $hrefArr = $htmlContent->find('.news-list .news__item-title')->parent()->parent()->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://segmentfault.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.segmentfault')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 虎嗅.
     * @param array $siteInfo
     */
    public function huxiu($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.huxiu')->info('start');
            $htmlContent = $this->queryList->get('https://www.huxiu.com/article/');

            $titleArr = $htmlContent->find('.article-items .article-item__img img')->attrs('alt')->all();
            $hrefArr = $htmlContent->find('.article-items .article-item__img')->parent()->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://www.huxiu.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.huxiu')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 果壳.
     * @param array $siteInfo
     */
    public function guokr($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.guokr')->info('start');
            $htmlContent = $this->queryList->get('https://www.guokr.com/');

            $titleArr = $htmlContent->find('#app .Banner__BannerWrap-sc-1vqe6cg-0 a')->texts()->all();
            $hrefArr = $htmlContent->find('#app .Banner__BannerWrap-sc-1vqe6cg-0 a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://www.guokr.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.guokr')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * github.
     * @param array $siteInfo
     */
    public function github($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.github')->info('start');
            $htmlContent = $this->queryList->get('https://github.com/trending');

//            $titleArr = $htmlContent->find(".Box .Box-row  .col-9")->texts()->all();
            $titleArr = $htmlContent->find('.Box .Box-row .lh-condensed a')->attrs('href')->all();
            $hrefArr = $htmlContent->find('.Box .Box-row .lh-condensed a')->attrs('href')->all();

            $newHrefArr = array_map(function ($path) {
                return 'https://www.github.com' . $path;
            }, $hrefArr);

            $topList = diy_array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.github')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 虎扑.
     * @param array $siteInfo
     */
    public function hupu($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.hupu')->info('start');
            $htmlContent = $this->queryList->get('https://bbs.hupu.com/all-gambia');

            $titleArr = $htmlContent->find('.list-item-wrap .t-title')->texts()->all();
            $hrefArr = $htmlContent->find('.list-item-wrap .t-info a')->attrs('href')->all();
            $newHrefArr = array_map(function ($path) {
                return 'https://bbs.hupu.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.hupu')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * 好奇心日报.
     * @param array $siteInfo
     */
    public function qdaily($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.qdaily')->info('start');
            $htmlContent = $this->queryList->get('http://www.qdaily.com/tags/29.html');

            $titleArr = $htmlContent->find('.page-content .packery-item .title')->texts()->all();
            $hrefArr = $htmlContent->find('.page-content .packery-item a')->attrs('href')->all();
            $newHrefArr = array_map(function ($path) {
                return 'http://www.qdaily.com' . $path;
            }, $hrefArr);

            $topList = array_combine($titleArr, $newHrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.qdaily')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }

    /**
     * csdn.
     * @param array $siteInfo
     */
    public function tieba($siteInfo = []): array
    {
        try {
            logger('crontab.GetTopArticle.tieba')->info('start');
            $htmlContent = $this->queryList->get('http://tieba.baidu.com/hottopic/browse/topicList')->getHtml();

            $content = json_decode($htmlContent, true)['data']['bang_topic']['topic_list'];

            $titleArr = array_column($content, 'topic_name');
            $hrefArr = array_column($content, 'topic_url');
            $topList = array_combine($titleArr, $hrefArr);
        } catch (\Exception $exception) {
            logger('crontab.GetTopArticle.tieba')->error($exception->getMessage());

            $topList = [];
        }

        return $topList;
    }
}
