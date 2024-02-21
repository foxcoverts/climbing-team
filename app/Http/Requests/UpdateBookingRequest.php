<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_at' => ['sometimes', 'required', 'date'],
            'end_at' => ['sometimes', 'required_with:start_at', 'date', 'after:start_at'],
            'status' => ['sometimes', 'required', Rule::enum(BookingStatus::class)],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'activity' => ['sometimes', 'required', 'string', 'max:255'],
            'group_name' => ['sometimes', 'required', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'start_at' => __('Start'),
            'end_at' => __('End'),
            'status' => __('Status'),
            'location' => __('Location'),
            'activity' => __('Activity'),
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
