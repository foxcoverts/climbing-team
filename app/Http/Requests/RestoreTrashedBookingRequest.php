<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestoreTrashedBookingRequest extends FormRequest
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
        return [
            'deleted_at' => ['required', 'declined'],
        ];
    }
}
