<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Step;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\Step
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
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'steps' => [
                'required',
                'array',
            ],
            'tip_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
