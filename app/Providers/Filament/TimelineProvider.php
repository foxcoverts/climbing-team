<?php

namespace App\Providers\Filament;

use App\Enums\Accreditation;
use App\Enums\BookingAttendeeStatus;
use App\Filament\Clusters\Admin;
use App\Models\Booking;
use App\Models\Key;
use App\Models\NewsPost;
use App\Models\User;
use Closure;
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
                'confirmed' => 'heroicon-o-check',
                'cancelled' => 'heroicon-o-x-mark',
                'restored' => 'heroicon-o-arrow-uturn-left',
            ])
            ->itemIconColors([
                'cancelled' => 'danger',
                'deleted' => 'danger',
                'activated' => 'success',
                'confirmed' => 'success',
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
            ->attributeValue('lead_instructor_id', fn ($value) => User::find($value)?->getFilamentName(), Booking::class)
            ->attributeLabel('lead_instructor_id', 'lead instructor')
            ->causerUrl(fn (?User $causer) => static::getRecordUrl($causer))
            ->eventDescription('activated', function (Activity|ActivityModel $activity, ?string $causerName): string|HtmlString {
                if (! $activity->causer || $activity->subject->is($activity->causer)) {
                    return __('Activated their account.');
                }

                return str(__('**:causerName** activated the account.', [
                    'causerName' => static::getRecordLink($activity->causer, $causerName),
                ]))->inlineMarkdown()->toHtmlString();
            }, User::class)
            ->eventDescription('kitChecked', function (Activity|ActivityModel $activity, ?string $causerName): string|HtmlString {
                return str(__('**:causerName** checked the user\'s kit.', [
                    'causerName' => static::getRecordLink($activity->causer, $causerName),
                ]))->inlineMarkdown()->toHtmlString();
            })
            ->eventDescription('transferred', function (Activity|ActivityModel $activity): string|HtmlString {
                $changes = [];
                if ($oldHolder = User::find(Arr::get($activity->changes(), 'old.holder_id'))) {
                    $changes[] = 'from '.static::getRecordLink($oldHolder);
                }
                if ($newHolder = User::find(Arr::get($activity->changes(), 'attributes.holder_id'))) {
                    $changes[] = 'to **'.static::getRecordLink($newHolder).'**';
                }

                return str(static::generateEventDescription($activity, implode(' ', $changes)))->inlineMarkdown()->toHtmlString();
            }, Key::class)
            ->eventDescription('responded', function (Activity|ActivityModel $activity): string|HtmlString {
                $status = BookingAttendeeStatus::tryFrom(data_get($activity->changes(), 'attributes.attendance.status'));
                $attendee = User::find(data_get($activity->changes(), 'attributes.attendance.attendee_id'));

                $replace = [
                    'attendeeName' => static::getRecordLink($attendee),
                    'modelLabel' => static::getRecordLabel($activity->subject_type, $activity->subject),
                ];

                $message = match ($status) {
                    BookingAttendeeStatus::Accepted => '**:attendeeName** will be attending the :modelLabel.',
                    BookingAttendeeStatus::Tentative => '**:attendeeName** may be able to attend the :modelLabel.',
                    BookingAttendeeStatus::Declined => '**:attendeeName** cannot attend the :modelLabel.',
                };

                return str(__($message, $replace))->inlineMarkdown()->toHtmlString();
            })
        );
    }

    protected static function generateEventDescription(Activity|ActivityModel $activity, ?string $changesSummary = null): string|HtmlString
    {
        $message = '';

        $replace = [
            'causerName' => static::getRecordLink($activity->causer),
            'event' => str($activity->event)->headline()->lower(),
            'modelLabel' => static::getRecordLabel($activity->subject_type, $activity->subject),
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

    public static function getRecordLink(?Model $record, ?string $title = null): string
    {
        if (is_null($title)) {
            $title = static::getRecordTitle($record);
        }
        if (empty($title)) {
            return '';
        }

        if ($url = static::getRecordUrl($record)) {
            return "[$title]($url)";
        }

        return $title;
    }

    public static function getRecordTitle(?Model $record): string
    {
        if (! $record) {
            return '';
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

        return '';
    }

    public static function getRecordUrl(?Model $record): ?string
    {
        if (! $record) {
            return null;
        }

        $getRecordUrl = fn ($resource) => $resource::hasPage('view') && $resource::canView($record)
            ? $resource::getUrl('view', ['record' => $record])
            : ($resource::hasPage('edit') && $resource::canEdit($record)
            ? $resource::getUrl('edit', ['record' => $record])
            : null);

        return static::withModelResource($record, $getRecordUrl, [
            fn ($resource) => ($resource::getCluster() === Admin::class),
            true,
        ]);
    }

    public static function getRecordLabel(string $recordClass, ?Model $record = null): ?string
    {
        $modelLabel = static::withModelResource($recordClass ?? $record, fn ($resource) => $resource::getModelLabel(), [
            fn ($resource): bool => $resource::getCluster() === Admin::class,
            true,
        ], fn (): string => \Filament\Support\get_model_label($recordClass ?? $record));

        return str($modelLabel)->lower();
    }

    /**
     * Walk through the resources for the given model and apply the callback, returning the first non-null value.
     *
     * @param  array  $filters  Additional filter that can be applied to check resource validity. If multiple filters are given then the resources are looped through again.
     * @param  mixed  $default  Default value (or callback) if all resources return null, or no resources are found.
     */
    protected static function withModelResource(string|Model $model, Closure $callback, Closure|array $filters = [], mixed $default = null): mixed
    {
        if ($model instanceof Model) {
            $model = $model::class;
        }
        if (is_callable($filters)) {
            $filters = [$filters];
        } elseif (empty($filters)) {
            $filters = [true];
        }

        if (class_exists(\Filament\Facades\Filament::class) && ($panel = \Filament\Facades\Filament::getCurrentPanel())) {
            foreach ($filters as $filter) {
                foreach ($panel->getResources() as $resource) {
                    if ($model !== $resource::getModel()) {
                        continue;
                    }
                    if (! $resource::canAccess()) {
                        continue;
                    }
                    if (is_callable($filter) && ! $filter($resource)) {
                        continue;
                    }

                    if (! is_null($value = $callback($resource))) {
                        return $value;
                    }
                }
            }
        }

        if (is_callable($default)) {
            return $default();
        }

        return $default;
    }
}
