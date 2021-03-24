<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\Order
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
            'orders' => [
                'required',
                'array',
            ],
            'user_id' => [
                'nullable',
                'integer',
            ],
            'tracking_number' => [
                'nullable',
                'string',
            ],
            'user_email' => [
                'nullable',
                'string',
            ],
            'total_summ' => [
                'numeric',
                'required',
            ],
            'payment' => [
                'array',
                'required',
            ],
            'address' => [
                'array',
                'required',
            ],
            'address.name' => [
                'string',
                'required',
            ],
            'address.phone' => [
                'string',
                'required',
            ],
            'address.address1' => [
                'string',
                'required',
            ],
            'address.address2' => [
                'string',
                'nullable',
            ],
            'address.postal_code' => [
                'string',
                'required',
            ],
            'address.city' => [
                'string',
                'required',
            ],
            'address.state' => [
                'string',
                'required',
            ],
            'exp_service' => [
                'string',
                'nullable',
            ],
            'insurance' => [
                'string',
                'nullable',
            ],
            'notes' => [
                'string',
            ],
            'ordered_status' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
