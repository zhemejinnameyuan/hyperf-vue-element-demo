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
namespace App\Request\System;

use Hyperf\Validation\Request\FormRequest;

/**
 * Class MenuRequest.
 */
class UserRequest extends FormRequest
{
    protected $scenes = [
        'add' => ['username', 'nickname', 'group_id', 'password'],
        'update' => ['id', 'username', 'nickname', 'group_id', 'password'],
        'delete' => ['id'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 定义验证规则的错误消息.
     */
    public function messages(): array
    {
        return [
            'id.required' => '缺少ID参数',
            'username.required' => '账号不能为空',
            'nickname.required' => '昵称不能为空',
            'group_id.required' => '请选择分组',
            'password.required_without' => '请输入密码',
        ];
    }

    /**
     * 验证规则.
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'username' => 'required',
            'nickname' => 'required',
            'group_id' => 'required',
            'password' => 'required_without:id',
        ];
    }
}
