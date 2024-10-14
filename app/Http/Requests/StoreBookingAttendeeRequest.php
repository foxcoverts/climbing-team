<?php

namespace App\Http\Requests;

use App\Enums\BookingAttendeeStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingAttendeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'status' => ['required', Rule::enum(BookingAttendeeStatus::class)->except(BookingAttendeeStatus::NeedsAction)],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id' => 'Attendee',
            'status' => 'Availability',
        ];
    }
}
