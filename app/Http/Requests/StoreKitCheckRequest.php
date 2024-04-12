<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKitCheckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['required', 'ulid', Rule::exists('users', 'id')],
            'checked_by_id' => ['required', 'ulid', Rule::exists('users', 'id')],
            'checked_on' => ['required', 'date', 'before:tomorrow'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
