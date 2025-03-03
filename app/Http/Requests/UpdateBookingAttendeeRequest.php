<?php

namespace App\Http\Requests;

use App\Enums\BookingAttendeeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingAttendeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(BookingAttendeeStatus::class)->except(BookingAttendeeStatus::NeedsAction)],
            'comment' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the labels for each attribute.
     *
     * @return array<string>
     */
    public function attributes(): array
    {
        return [
            'status' => __('Availability'),
        ];
    }
}
