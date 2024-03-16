<?php

namespace App\Http\Requests;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Enums\Section;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user->id)],
            'phone' => ['nullable', 'phone:INTERNATIONAL,GB'],
            'emergency_name' => ['nullable', 'required_with:emergency_phone', 'string', 'max:100'],
            'emergency_phone' => ['nullable', 'required_with:emergency_name', 'phone:INTERNATIONAL,GB'],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
            'section' => ['required', Rule::enum(Section::class)],
            'role' => ['required', Rule::enum(Role::class)],
        ];

        if ($this->user()->can('accredit', $this->user)) {
            $rules['accreditations.*'] = [Rule::enum(Accreditation::class)];
        }

        return $rules;
    }
}
