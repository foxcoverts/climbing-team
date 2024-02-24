<?php

namespace App\Http\Requests;

use App\Enums\Accreditation;
use App\Enums\Role;
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user->id)],
            'timezone' => ['required', 'string', 'max:100', 'timezone:all'],
        ];

        if ($this->user()->can('manage', $this->user)) {
            $rules['role'] = ['required', Rule::enum(Role::class)];
            $rules['accreditation.*'] = [Rule::enum(Accreditation::class)];
        }

        return $rules;
    }
}
