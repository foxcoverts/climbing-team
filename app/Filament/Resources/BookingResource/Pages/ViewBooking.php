<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (): string => Admin\Resources\BookingResource::getUrl('edit', ['record' => $this->getRecord()]))
                    ->visible(fn (): bool => Gate::check('update', $this->getRecord())),
            ]),
        ];
    }
}
