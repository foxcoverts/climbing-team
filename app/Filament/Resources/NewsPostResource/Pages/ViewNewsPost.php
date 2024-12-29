<?php

namespace App\Filament\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\NewsPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ViewNewsPost extends ViewRecord
{
    protected static string $resource = NewsPostResource::class;

    public function getHeading(): string|Htmlable
    {
        return $this->getRecordTitle();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (): string => Admin\Resources\NewsPostResource::getUrl('edit', ['record' => $this->getRecord()]))
                    ->visible(fn (): bool => Gate::check('update', $this->getRecord())),
            ]),
        ];
    }
}
