<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\Setting
 */
class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'general_settings' => [
                'required',
                'array',
            ],
            'content' => [
                'nullable',
                'array',
            ],
            'code_insert' => [
                'nullable',
                'string',
            ],
            'terms' => [
                'nullable',
                'string',
            ],
        ];
    }
}
