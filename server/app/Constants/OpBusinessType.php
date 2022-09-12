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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * 操作业务类型
 * Class OpBusinessType
 */
class OpBusinessType extends AbstractConstants
{
    /**
     * @Message("系统设置")
     */
    const SYSTEM = 1;

    /**
     * @Message("今日热榜")
     */
    const HOT_ARTICLE = 2;
}
