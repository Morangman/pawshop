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
            'user_email' => [
                'nullable',
                'string',
            ],
            'total_summ' => [
                'integer',
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
