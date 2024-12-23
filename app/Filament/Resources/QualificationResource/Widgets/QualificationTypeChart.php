<?php

namespace App\Filament\Resources\QualificationResource\Widgets;

use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QualificationTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Qualification Holders by Type';

    protected function getData(): array
    {
        $data = Qualification::query()
            ->select('detail_type', DB::raw('count(DISTINCT user_id) as count'))
            ->groupBy('detail_type')
            ->pluck('count', 'detail_type');

        return [
            'datasets' => [
                [
                    'data' => $data->values(),
                    'backgroundColor' => $data->keys()->map(fn ($value) => match ($value) {
                        GirlguidingQualification::class => 'rgb(22, 27, 78)',
                        MountainTrainingQualification::class => 'rgb(0, 163, 180)',
                        ScoutPermit::class => 'rgb(99, 16, 188)',
                        default => ''
                    }),
                ],
            ],
            'labels' => $data->keys()->map(fn ($value) => __("app.qualification.type.$value")),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
