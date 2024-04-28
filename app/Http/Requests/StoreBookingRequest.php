<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'start_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
            'location' => ['required', 'string', 'max:255'],
            'activity' => ['required', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'lead_instructor_notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'start_date' => __('Date'),
            'start_time' => __('Start'),
            'end_time' => __('End'),
            'timezone' => __('Timezone'),
            'location' => __('Location'),
            'activity' => __('Activity'),
            'group_name' => __('Group Name'),
            'notes' => __('Notes'),
            'lead_instructor_notes' => __('Lead Instructor Notes'),
        ];
    }
}
