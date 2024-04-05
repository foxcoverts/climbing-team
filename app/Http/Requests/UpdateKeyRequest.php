<?php

namespace App\Http\Requests;

use App\Models\Key;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKeyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique(Key::class)->ignore($this->key)],
            'holder_id' => ['required', Rule::exists(User::class)],
        ];
    }
}
