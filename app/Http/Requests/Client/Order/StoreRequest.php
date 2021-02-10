<?php

declare(strict_types = 1);

namespace App\Http\Requests\Client\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Client\Order
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
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
            'notes' => [
                'string',
            ],
            'ordered_product' => [
                'nullable',
                'array',
            ],
            'ordered_status' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
