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
 */
class UserCode extends AbstractConstants
{
    /**
     * @Message("登陆成功")
     */
    const SUCCESS = 1;

    /**
     * @Message("密码错误")
     */
    const PASSWORD_ERROR = 1001;

    /**
     * @Message("密码输入错误次数过多,请联系管理员")
     */
    const MAX_PASSWORD_ERROR = 5;

    /**
     * @Message("用户不存在")
     */
    const NOT_FOUND = 1002;

    /**
     * @Message("用户被禁用")
     */
    const LOCKED = 1003;
}
