<?php

namespace App\Http\Requests;

use App\Enums\CommentNotificationOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNotificationSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment_mail' => ['nullable', Rule::enum(CommentNotificationOption::class)],
            'invite_mail' => ['nullable', 'boolean'],
            'change_mail' => ['nullable', 'boolean'],
            'confirm_mail' => ['nullable', 'boolean'],
            'cancel_mail' => ['nullable', 'boolean'],
        ];
    }
}
