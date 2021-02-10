<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\Comment
 */
class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'string',
                'max:255',
            ],
            'text' => [
                'required',
            ],
            'value' => [
                'required',
            ],
            'is_hidden' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
