<?php

namespace App\Http\Requests;

use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Models\MountainTrainingQualification;
use App\Models\ScoutPermit;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreQualificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'detail_type' => ['required', Rule::in([
                MountainTrainingQualification::class,
                ScoutPermit::class,
            ])],
            ...match ($this->detail_type) {
                MountainTrainingQualification::class => $this->mountainTrainingRules(),
                ScoutPermit::class => $this->scoutPermitRules(),
                default => [],
            },
        ];
    }

    protected function mountainTrainingRules(): array
    {
        return [
            'award' => [
                'required',
                'string',
                Rule::enum(MountainTrainingAward::class),
                Rule::unique('mountain_training_qualifications')
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
            'activity' => ['required', 'string', Rule::enum(ScoutPermitActivity::class)],
            'category' => ['required', 'string', Rule::enum(ScoutPermitCategory::class)],
            'permit_type' => ['required', Rule::enum(ScoutPermitType::class)],
            'restrictions' => ['nullable', 'string'],
        ];
    }
}
