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
            'start_date' => ['sometimes', 'required', 'date'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'required', 'date_format:H:i', 'after:start_time'],
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
            'start_date' => __('Date'),
            'start_time' => __('Start'),
            'end_time' => __('End'),
            'status' => __('Status'),
            'location' => __('Location'),
            'activity' => __('Activity'),
            'group_name' => __('Group Name'),
            'notes' => __('Notes'),
        ];
    }
}
