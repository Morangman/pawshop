<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Faq;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\Faq
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
            'data' => [
                'required',
                'array',
            ],
            'name' => [
                'required',
                'string',
            ],
        ];
    }
}
