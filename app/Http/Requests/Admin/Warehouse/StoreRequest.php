<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\Warehouse
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
            'category_id' => [
                'nullable',
                'integer',
            ],
            'order_id' => [
                'nullable',
                'integer',
            ],
            'status' => [
                'required',
                'integer',
            ],
            'product_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'imei' => [
                'nullable',
                'string',
                'max:255',
            ],
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'price' => [
                'nullable',
                'numeric',
            ],
            'clear_price' => [
                'nullable',
                'numeric',
            ],
            'delivery_price' => [
                'nullable',
                'numeric',
            ],
            'repair_price' => [
                'nullable',
                'numeric',
            ],
            'sell_price' => [
                'nullable',
                'numeric',
            ],
            'is_locked' => [
                'nullable',
            ],
            'steps' => [
                'nullable',
                'array',
            ],
        ];
    }
}
