<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;

class DestroyBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking.status' => ['required', 'in:cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking.status.in' => 'You must cancel this booking before you can delete it.',
        ];
    }

    public function all($keys = null): array
    {
        $results = parent::all($keys);
        Arr::set($results, 'booking', $this->route('booking')->toArray());
        return $results;
    }
}
