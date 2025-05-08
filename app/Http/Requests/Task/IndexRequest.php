<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:pending,in_progress,completed,new'],
            'due_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'The status must be one of: pending, in_progress, completed',
            'due_date.date_format' => 'The due date must be in format Y-m-d',
            'per_page.min' => 'The per page value must be at least 1',
            'per_page.max' => 'The per page value cannot be greater than 100',
            'page.min' => 'The page number must be at least 1',
        ];
    }
} 