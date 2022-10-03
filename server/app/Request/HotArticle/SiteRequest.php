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
namespace App\Request\HotArticle;

use Hyperf\Validation\Request\FormRequest;

/**
 * Class SiteRequest.
 */
class SiteRequest extends FormRequest
{
    protected $scenes = [
        'add' => ['name', 'english_name', 'url', 'type_id'],
        'update' => ['id', 'name', 'english_name', 'url', 'type_id'],
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
            'name.required' => '请填写网站名称',
            'english_name.required' => '请填写网站英文名称',
            'url.required' => '请填写网站链接',
            'type_id.required' => '请选择网站所属分类',
        ];
    }

    /**
     * 验证规则.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'english_name' => 'required',
            'url' => 'required',
            'type_id' => 'required',
        ];
    }
}
