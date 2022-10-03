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
class ConfigRequest extends FormRequest
{
    protected $scenes = [
        'add' => ['key', 'value', 'desc', 'type'],
        'update' => ['id', 'key', 'value', 'desc', 'type'],
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
            'key.required' => '配置项不能为空',
            'value.required' => '值不能为空',
            'desc.required' => '描述不能为空',
            'type.required' => '类型不能为空',
        ];
    }

    /**
     * 验证规则.
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'key' => 'required',
            'value' => 'required',
            'desc' => 'required',
            'type' => 'required',
        ];
    }
}
