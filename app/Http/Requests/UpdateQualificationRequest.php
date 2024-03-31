<?php

namespace App\Http\Requests;

use App\Enums\GirlguidingScheme;
use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\ScoutPermit;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
            GirlguidingQualification::class => $this->girlguidingRules(),
            MountainTrainingQualification::class => $this->mountainTrainingRules(),
            ScoutPermit::class => $this->scoutPermitRules(),
            default => [],
        };
    }

    protected function girlguidingRules(): array
    {
        return [
            'expires_on' => ['required', 'date'],
            'scheme' => ['required', Rule::enum(GirlguidingScheme::class)],
            'level' => ['required', 'integer', 'between:1,2'],
        ];
    }

    protected function mountainTrainingRules(): array
    {
        return [
            'award' => [
                'required',
                Rule::enum(MountainTrainingAward::class),
                Rule::unique('mountain_training_qualifications')
                    ->ignore($this->qualification)
                    ->where(fn (Builder $query) => $query
                        ->whereExists(fn (Builder $where) => $where->select(DB::raw(1))
                            ->from('qualifications')
                            ->where('qualifications.detail_type', MountainTrainingQualification::class)
                            ->whereColumn('mountain_training_qualifications.id', 'qualifications.detail_id')
                            ->where('qualifications.user_id', $this->user->id)
                        )
                    ),
            ],
        ];
    }

    protected function scoutPermitRules(): array
    {
        return [
            'expires_on' => ['required', 'date'],
            'activity' => ['required', Rule::enum(ScoutPermitActivity::class)],
            'category' => ['required', Rule::enum(ScoutPermitCategory::class)],
            'permit_type' => ['required', Rule::enum(ScoutPermitType::class)],
            'restrictions' => ['nullable', 'string'],
        ];
    }
}
