<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages\Concerns;

use App\Enums\BookingStatus;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

trait MutatesFormData
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['date'] = Carbon::parse($data['start_at'])->timezone($data['timezone']);
        $data['start_time'] = Carbon::parse($data['start_at'])->timezone($data['timezone']);
        $data['end_time'] = Carbon::parse($data['end_at'])->timezone($data['timezone']);

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return parent::mutateFormDataBeforeSave(
            $this->mutateFormDataBefore($data)
        );
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return parent::mutateFormDataBeforeCreate(
            $this->mutateFormDataBefore($data)
        );
    }

    protected function mutateFormDataBefore(array $data): array
    {
        $start_at = null;
        $end_at = null;
        $timezone = $data['timezone'] ?? null;

        if ($date = data_get($data, 'date')) {
            if ($start_time = data_get($data, 'start_time')) {
                $start_at = Carbon::parse($date.'T'.$start_time)->shiftTimezone($timezone)->utc();
            }
            if ($end_time = data_get($data, 'end_time')) {
                $end_at = Carbon::parse($date.'T'.$end_time)->shiftTimezone($timezone)->utc();
            }
        }

        if (data_get($data, 'confirm', false)) {
            $data['status'] = BookingStatus::Confirmed->value;
        }
        if (data_get($data, 'restore', false)) {
            $data['status'] = BookingStatus::Tentative->value;
        }

        return [
            'start_at' => $start_at,
            'end_at' => $end_at,
            ...Arr::except($data, ['date', 'start_time', 'end_time', 'confirm', 'restore']),
        ];
    }
}
