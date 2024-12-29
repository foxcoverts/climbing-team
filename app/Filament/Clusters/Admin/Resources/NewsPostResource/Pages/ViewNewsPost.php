<?php

namespace App\Filament\Clusters\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin\Resources\NewsPostResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Filament\Resources\NewsPostResource as TeamNewsPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use RalphJSmit\Filament\Activitylog;

class ViewNewsPost extends ViewRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = NewsPostResource::class;

    public function getHeading(): string|Htmlable
    {
        return $this->getRecordTitle();
    }

    public function getSubheading(): string|Htmlable|null
    {
        $link = TeamNewsPostResource::getUrl('view', ['record' => $this->getRecord()]);
        $path = parse_url($link, PHP_URL_PATH);

        return str("[$path]($link)")->inlineMarkdown()->toHtmlString();
    }

    protected function getHeaderActions(): array
    {
        return [
            ActivityLog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
