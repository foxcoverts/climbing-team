<?php

namespace App\Filament\Clusters\My\Pages;

use App\Enums\CommentNotificationOption;
use App\Models\NotificationSettings as NotificationSettingsModel;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Exceptions\Halt;

class NotificationSettings extends Page
{
    use HasUnsavedDataChangesAlert, InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $activeNavigationIcon = 'heroicon-s-bell';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.clusters.my.pages.notification-settings';

    public ?array $data = [];

    protected ?NotificationSettingsModel $record = null;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getRecord()?->attributesToArray();

        $this->form->fill($data);
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            static::getUrl() => static::getNavigationLabel(),
            'Edit',
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->getRecord())
            ->operation('edit')
            ->statePath('data')
            ->schema([
                Components\Section::make()
                    ->schema([
                        $this->makeNotificationSelect('invite_mail')
                            ->label('Invites')
                            ->helperText('Get an e-mail when you are invited to something.'),
                        $this->makeNotificationSelect('change_mail')
                            ->label('Changes')
                            ->helperText('Get an e-mail when something is changed.'),
                        $this->makeNotificationSelect('confirm_mail')
                            ->label('Confirmed')
                            ->helperText('Get an e-mail when something is confirmed.'),
                        $this->makeNotificationSelect('cancel_mail')
                            ->label('Confirmed')
                            ->helperText('Get an e-mail when something is cancelled.'),
                        $this->makeNotificationSelect('comment_mail', options: CommentNotificationOption::class)
                            ->label('Comments')
                            ->helperText('Get an e-mail when a comment is made.'),
                    ]),
            ]);
    }

    protected function makeNotificationSelect(string $field, $options = [true => 'On', false => 'Off']): Components\Select
    {
        return Components\Select::make($field)
            ->placeholder(function (Components\Select $component) {
                $default = NotificationSettingsModel::default($component->getName());

                if ($default instanceof HasLabel) {
                    $default = $default->getLabel();
                } else {
                    if ($default instanceof \BackedEnum) {
                        $default = $default->value;
                    } elseif ($default instanceof \UnitEnum) {
                        $default = $default->name;
                    }
                    $default = data_get($component->getOptions(), $default, $default);
                }

                return __('Default (:Value)', [
                    'value' => $default,
                ]);
            })
            ->options($options);
    }

    public function save(): void
    {
        try {
            $record = $this->getRecord();
            $record->fill($this->form->getState());
            $record->save();
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    public function clear(): void
    {
        try {
            $record = $this->getRecord();
            $record->delete();
        } catch (Halt $exception) {
            return;
        }

        $this->clearRecord()->fillForm();

        Notification::make()
            ->success()
            ->title('Cleared')
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getClearFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->record($this->getRecord())
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getClearFormAction(): Action
    {
        return Action::make('clear')
            ->record($this->getRecord())
            ->visible(fn (NotificationSettingsModel $record) => $record->exists)->label('Clear')
            ->color('danger')
            ->action('clear');
    }

    public function clearRecord(): static
    {
        $this->record = null;

        return $this;
    }

    public function getRecord(): ?NotificationSettingsModel
    {
        if (! is_null($this->record)) {
            return $this->record;
        }

        if (is_null($user = Filament::auth()->user())) {
            return null;
        }

        $this->record = NotificationSettingsModel::query()
            ->whereBelongsTo($user)
            ->whereMorphedTo('notifiable', null)
            ->firstOr(function () use ($user): NotificationSettingsModel {
                $settings = new NotificationSettingsModel;
                $settings->user()->associate($user);

                return $settings;
            });

        return $this->record;
    }
}
