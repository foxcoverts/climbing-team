<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class GotoRecord extends Page
{
    use InteractsWithRecord {
        configureAction as configureActionRecord;
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->redirect(static::getResource()::getUrl('view', ['record' => $this->getRecord()]));
    }
}
