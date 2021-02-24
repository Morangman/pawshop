<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Admin\Task
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
            'text' => [
                'required',
                'string',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'task_status' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
