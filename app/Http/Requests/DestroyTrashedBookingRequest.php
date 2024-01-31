<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyTrashedBookingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->errorBag = 'bookingDeletion';
        return [
            'confirm' => ['bail', 'required', 'string', 'uppercase', 'in:DELETE']
        ];
    }

    public function messages(): array
    {
        return [
            'confirm.uppercase' => 'Please type "DELETE" in capital letters to confirm.',
            'confirm.in' => 'Please type "DELETE" to confirm.',
        ];
    }
}
