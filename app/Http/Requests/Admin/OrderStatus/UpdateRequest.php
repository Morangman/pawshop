<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\OrderStatus;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\OrderStatus
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
            'name' => [
                'required',
                'string',
            ],
            'color' => [
                'nullable',
                'string',
            ],
            'fedex_status' => [
                'nullable',
                'string',
            ],
            'order' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
