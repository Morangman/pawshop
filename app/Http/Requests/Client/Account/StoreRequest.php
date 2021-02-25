<?php

declare(strict_types = 1);

namespace App\Http\Requests\Client\Account;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Client\Account
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
                "unique:users,email,{$this->request->get('id')}",
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
            'addresses' => [
                'nullable',
                'array',
            ],
        ];
    }
}
