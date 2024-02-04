<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
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
            'status' => ['required', Rule::enum(BookingStatus::class)],
            'location' => ['required', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'start_at' => __('Start'),
            'end_at' => __('End'),
            'status' => __('Status'),
            'location' => __('Location'),
            'group_name' => __('Group Name'),
            'notes' => __('Notes'),
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
