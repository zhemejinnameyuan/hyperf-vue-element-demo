<?php

declare(strict_types=1);

namespace App\Request;


use Hyperf\Validation\Request\FormRequest;

class AesRequest extends FormRequest
{
    protected $scenes = [
        'update' => ['name','url','startTime','endTime','age','account'],
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
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'id' => 'required',
            'url' => 'required|active_url',
            'startTime' => 'required|date|after:today',
            'endTime' => 'required|date|after:startTime',
            'age' => 'required|digits_between:1,5',
            'account'=> 'required|exists:sys_user,username',
        ];
    }

}
