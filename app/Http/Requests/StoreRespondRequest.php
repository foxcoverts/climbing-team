<?php

namespace App\Http\Requests;

use App\Enums\AttendeeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRespondRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(AttendeeStatus::class)->except(AttendeeStatus::NeedsAction)],
        ];
    }
}
