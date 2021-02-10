<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Admin\User
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
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                'max:255',
                "unique:users,email,{$this->request->get('id')}",
            ],
            'password' => [
                'nullable',
                'min:6',
                'confirmed',
            ],
            'phone' => ['required', 'string'],
        ];
    }
}
