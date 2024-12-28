<?php

namespace App\Providers\Filament;

use App\Enums\Accreditation;
use App\Models\Key;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Filament\Activitylog\Infolists\Components\Timeline;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class TimelineProvider extends ServiceProvider
{
    public function boot(): void
    {
        Timeline::configureUsing(fn (Timeline $timeline) => $timeline
            ->itemIcons([
                'created' => 'heroicon-o-plus-circle',
                'updated' => 'heroicon-o-pencil',
                'deleted' => 'heroicon-o-trash',
                'activated' => 'heroicon-o-check',
                'kitChecked' => 'heroicon-o-document-check',
                'transferred' => 'heroicon-o-arrows-right-left',
            ])
            ->itemIconColors([
                'deleted' => 'danger',
                'activated' => 'success',
                'kitChecked' => 'info',
                'transferred' => 'info',
            ])
            ->attributeValue('accreditations', function (Collection $value) {
                if ($value->isEmpty()) {
                    return null;
                }

                return $value
                    ->sort(fn (Accreditation $a, Accreditation $b) => $a->compare($b))
                    ->map(fn (Accreditation $accreditation) => $accreditation->getLabel())
                    ->join(', ', ' and ');
            })
            ->attributeValue('author_id', fn ($value) => User::find($value)?->getFilamentName(), NewsPost::class)
            ->attributeLabel('author_id', 'author', NewsPost::class)
            ->attributeValue('holder_id', fn ($value) => User::find($value)?->getFilamentName(), Key::class)
            ->attributeLabel('holder_id', 'holder', Key::class)
            ->causerUrl(fn (?User $causer) => $this->getRecordUrl($causer))
            ->eventDescription('activated', function (Activity|ActivityModel $activity, ?string $causerName): string|HtmlString {
                if (! $activity->causer || $activity->subject->is($activity->causer)) {
                    return __('Activated their account.');
                }

                return __('**:causerName** activated the account.', [
                    'causerName' => $this->getRecordLink($activity->causer, $causerName),
                ]);
            }, User::class)
            ->eventDescription('kitChecked', function (Activity|ActivityModel $activity, ?string $causerName): string|HtmlString {
                return __('**:causerName** checked the user\'s kit.', [
                    'causerName' => $this->getRecordLink($activity->causer, $causerName),
                ]);
            })
            ->eventDescription('transferred', function (Activity|ActivityModel $activity): string|HtmlString {
                $changes = [];
                if ($oldHolder = User::find(Arr::get($activity->changes(), 'old.holder_id'))) {
                    $changes[] = 'from '.$this->getRecordLink($oldHolder);
                }
                if ($newHolder = User::find(Arr::get($activity->changes(), 'attributes.holder_id'))) {
                    $changes[] = 'to **'.$this->getRecordLink($newHolder).'**';
                }

                return $this->generateEventDescription($activity, implode(' ', $changes));
            }, Key::class)
            ->modifyEventDescriptionUsing(function (string|HtmlString $eventDescription, Activity $activity, string $recordTitle, ?string $causerName, ?string $changesSummary) {
                $recordTitle = $this->getRecordLink($activity->subject, $recordTitle);

                return str("{$recordTitle} | {$eventDescription}")->inlineMarkdown()->toHtmlString();
            })
        );
    }

    protected function generateEventDescription(Activity|ActivityModel $activity, ?string $changesSummary = null): string|HtmlString
    {
        $message = '';

        $replace = [
            'causerName' => $this->getRecordLink($activity->causer),
            'event' => str($activity->event)->headline()->lower(),
            'modelLabel' => $this->getRecordLabel($activity->subject_type, $activity->subject),
            'changesSummary' => $changesSummary,
        ];

        if ($replace['causerName'] && $replace['changesSummary']) {
            $message = '**:causerName** :event the :modelLabel :changesSummary.';
        } elseif ($replace['causerName']) {
            $message = '**:causerName** :event the :modelLabel.';
        } elseif ($replace['changesSummary']) {
            $message = 'The :modelLabel was :event :changesSummary';
        } else {
            $message = 'The :modelLabel was :event.';
        }

        return __($message, $replace);
    }

    protected function getRecordLink(?Model $record, ?string $title = null): ?string
    {
        if (! $title ??= $this->getRecordTitle($record)) {
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
