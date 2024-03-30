<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SetupAccountLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'in:' . $this->user->email],
        ];
    }

    /**
     * Get the URL to redirect to on a validation error.
     */
    protected function getRedirectUrl(): string
    {
        $url = $this->redirector->getUrlGenerator();

        return $url->route('setup-account-link', $this->user);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.in' => 'The :attribute does not match the one we have on record.',
        ];
    }
}
