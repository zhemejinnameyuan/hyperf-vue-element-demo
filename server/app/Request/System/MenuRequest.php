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
class MenuRequest extends FormRequest
{
    protected $scenes = [
        'add' => ['title', 'path', 'component'],
        'update' => ['id', 'title', 'path', 'component'],
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
            'title.required' => '菜单名称不能为空',
            'path.required' => '前端路由路径 不能为空',
            'component.required' => '组件(视图路径) 不能为空',
        ];
    }

    /**
     * 验证规则.
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'path' => 'required',
            'component' => 'required',
        ];
    }
}
