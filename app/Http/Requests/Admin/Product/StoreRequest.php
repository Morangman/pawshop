<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\Product
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
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
            'custom_text' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
