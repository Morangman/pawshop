<?php

declare(strict_types = 1);

namespace App\Http\Requests\Client\Callback;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Client\Callback
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
                'required',
                'string',
                'max:255',
                'email:rfc,dns',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:255',
            ],
            'sender' => [
                'nullable',
                'integer',
            ],
            'viewed' => [
                'nullable',
                'integer',
            ],
            'text' => [
                'nullable',
                'string',
            ],
        ];
    }
}
