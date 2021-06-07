<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\Coupon
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
                'integer',
            ],
            'name' => [
                'required',
                'string',
            ],
            'code' => [
                'required',
                'string',
            ],
            'percent_value' => [
                'required',
                'numeric',
            ],
            'text' => [
                'required',
                'string',
            ],
            'is_hidden' => [
                'nullable',
                'boolean',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}
