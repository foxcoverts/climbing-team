<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class GotoBooking extends Page
{
    use InteractsWithRecord {
        configureAction as configureActionRecord;
    }

    protected static string $resource = BookingResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->redirect(BookingResource::getUrl('view', ['record' => $this->getRecord()]));
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }
}
