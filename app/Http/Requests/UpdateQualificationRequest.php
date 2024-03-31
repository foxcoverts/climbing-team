<?php

namespace App\Http\Requests;

use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Models\ScoutPermit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQualificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->qualification->detail_type) {
            ScoutPermit::class => $this->scoutPermitRules(),
            default => [],
        };
    }

    protected function scoutPermitRules(): array
    {
        return [
            'expires_on' => ['required', 'date'],
            'activity' => ['required', 'string', Rule::enum(ScoutPermitActivity::class)],
            'category' => ['required', 'string', Rule::enum(ScoutPermitCategory::class)],
            'permit_type' => ['required', Rule::enum(ScoutPermitType::class)],
            'restrictions' => ['nullable', 'string'],
        ];
    }
}
