<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @todo
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'location' => ['required', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'start_at' => 'Start',
            'end_at' => 'End',
            'location' => 'Location',
            'group_name' => 'Group Name',
            'notes' => 'Notes',
        ];
    }

    /**
     * Get the custom validation error messages.
     *
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            'start_at.after' => 'The :attribute field must be a date in the future.'
        ];
    }
}
