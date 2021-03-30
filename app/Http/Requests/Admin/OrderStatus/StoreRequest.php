<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\OrderStatus;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\OrderStatus
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
            ],
            'color' => [
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
