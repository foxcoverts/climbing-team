<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRelatedBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'related_id' => [
                'required', Rule::exists(Booking::class, 'id'), Rule::unique('bookables', 'bookable_id')
                    ->where('bookable_type', Booking::class)
                    ->where('booking_id', $this->booking->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'related_id.unique' => 'These bookings have already been related.',
        ];
    }
}
