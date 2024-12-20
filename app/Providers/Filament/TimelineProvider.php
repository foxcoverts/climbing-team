<?php

namespace App\Providers\Filament;

use App\Models\Key;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Filament\Activitylog\Infolists\Components\Timeline;
use Spatie\Activitylog\Contracts\Activity;

class TimelineProvider extends ServiceProvider
{
    public function boot(): void
    {
        Timeline::configureUsing(fn (Timeline $timeline) => $timeline
            ->itemIcons([
                'created' => 'heroicon-o-plus-circle',
                'updated' => 'heroicon-o-pencil',
                'deleted' => 'heroicon-o-trash',
                'transferred' => 'heroicon-o-arrows-right-left',
            ])
            ->itemIconColors([
                'created' => 'success',
                'deleted' => 'danger',
                'transferred' => 'info',
            ])
            ->attributeValue('holder_id', fn (string $value) => User::find($value)?->getFilamentName(), Key::class)
            ->eventDescription('*', $this->generateEventDescriptionCallback($timeline))
            ->eventDescription('transferred', function (Activity $activity) use ($timeline): HtmlString {
                $replace = [];
                $changes = [];
                if ($oldHolder = User::find(Arr::get($activity->changes(), 'old.holder_id'))) {
                    $replace['oldHolderName'] = $this->getRecordLink($oldHolder);
                    $changes[] = 'from :oldHolderName';
                }
                if ($newHolder = User::find(Arr::get($activity->changes(), 'attributes.holder_id'))) {
                    $replace['newHolderName'] = $this->getRecordLink($newHolder);
                    $changes[] = 'to **:newHolderName**';
                }

                return $this->generateEventDescription($activity, $timeline->getRecord(), implode(' ', $changes), $replace);
            }, Key::class)
        );
    }

    protected function generateEventDescriptionCallback(Timeline $timeline): Closure
    {
        return fn (Activity $activity, ?string $changesSummary): HtmlString => $this->generateEventDescription($activity, $timeline->getRecord(), $changesSummary);
    }

    protected function generateEventDescription(Activity $activity, ?Model $record = null, ?string $changesSummary = null, array $replace = []): string|HtmlString
    {
        $formattedEvent = str($activity->event)->headline()->lower();
        $subjectLabel = $this->getRecordLabel($activity->subject_type, $activity->subject);

        $message = '';
        $wrap = '';

        if ($activity->event == 'created' || $activity->event == 'deleted') {
            $changesSummary = '';
        }

        $replace['causerName'] = $this->getRecordLink($activity->causer);

        if ($activity->subject && ! $record) {
            $replace['subjectName'] = $this->getRecordLink($activity->subject);
        }

        if (data_get($replace, 'causerName') && data_get($replace, 'subjectName') && $activity->event == 'updated' && filled($changesSummary)) {
            $message = "**:causerName** $formattedEvent $changesSummary for :subjectName";
        } elseif (data_get($replace, 'causerName') && data_get($replace, 'subjectName')) {
            $message = "**:causerName** $formattedEvent :subjectName $changesSummary";
        } elseif (data_get($replace, 'causerName') && $activity->event == 'updated' && filled($changesSummary)) {
            $message = "**:causerName** $formattedEvent $changesSummary";
        } elseif (data_get($replace, 'causerName')) {
            $message = "**:causerName** $formattedEvent the $subjectLabel $changesSummary";
        } elseif (data_get($replace, 'subjectName')) {
            $message = ":subjectName was $formattedEvent $changesSummary";
        } else {
            $message = "The $subjectLabel was $formattedEvent $changesSummary";
        }

        if ($activity->subject_id && ! $activity->subject && $activity->event != 'deleted') {
            // The subject no longer exists
            $wrap = '~~';
        }

        return str(__($wrap.trim($message).$wrap.'.', $replace))->inlineMarkdown()->toHtmlString();
    }

    protected function getRecordLink(?Model $record): ?string
    {
        if (! $title = $this->getRecordTitle($record)) {
            return null;
        }

        if ($url = $this->getRecordUrl($record)) {
            return "[$title]($url)";
        }

        return $title;
    }

    protected function getRecordTitle(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        if ($record instanceof \Filament\Models\Contracts\HasName) {
            return e(trim($record->getFilamentName()));
        }

        // Cannot directly access the attribute, since that could potentially trigger a `Model::preventAccessingMissingAttributes()` exception.
        if ($record->getAttributes()['name'] ?? null) {
            return e(trim($record->name));
        }

        if ($record->getAttributes()['title'] ?? null) {
            return e(trim($record->title));
        }

        return null;
    }

    protected function getRecordUrl(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        if (class_exists(\Filament\Facades\Filament::class) && ($panel = \Filament\Facades\Filament::getCurrentPanel())) {
            /** @var \Filament\Resources\Resource $modelResource */
            $modelResource = $panel->getModelResource($record);

            if ($modelResource && ($modelResource::hasPage('view') || $modelResource::hasPage('edit'))) {
                return $modelResource::hasPage('view') && $modelResource::canView($record)
                    ? $modelResource::getUrl('view', ['record' => $record])
                    : ($modelResource::hasPage('edit') && $modelResource::canEdit($record) ? $modelResource::getUrl('edit', ['record' => $record]) : null);
            }
        }

        return null;
    }

    public function getRecordLabel(string $recordClass, ?Model $record = null): ?string
    {
        $modelLabel = null;

        if (class_exists(\Filament\Facades\Filament::class) && ($panel = \Filament\Facades\Filament::getCurrentPanel())) {
            /** @var \Filament\Resources\Resource $modelResource */
            $modelResource = $panel->getModelResource($recordClass ?? $record);

            if ($modelResource) {
                $modelLabel = $modelResource::getModelLabel();
            }
        }

        $modelLabel ??= \Filament\Support\get_model_label($recordClass ?? $record);

        return str($modelLabel)->lower();
    }
}
