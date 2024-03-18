<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'phone:INTERNATIONAL,GB'],
            'emergency_name' => ['nullable', 'required_with:emergency_phone', 'string', 'max:100'],
            'emergency_phone' => ['nullable', 'required_with:emergency_name', 'phone:INTERNATIONAL,GB'],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
        ];
    }
}
