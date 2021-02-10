<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\Product
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
            'category_id' => [
                'required',
                'numeric',
                'exists:categories,id',
            ],
            'title' => [
                'required',
                'string',
            ],
            'general_info' => [
                'required',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'old_price' => [
                'numeric',
            ],
            'variations' => [
                'array'
            ],
            'recommended_products' => [
                'array'
            ],
            'seo' => [
                'array'
            ],
            'is_hidden' => [
                'nullable',
                'boolean',
            ],
            'ip_address' => [
                'nullable',
                'string',
            ],
            'product_images' => [
                'nullable',
                'array',
            ],
            'image' => [
                'nullable',
            ],
            'product_images.*' => [
                'nullable',
                'file',
                'mimes:pdf,png,jpeg,jpg,gif',
            ],
        ];
    }
}
