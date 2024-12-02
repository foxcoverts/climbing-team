<?php

namespace App\Filament\Resources\KitCheckResource\Pages;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Filament\Resources\KitCheckResource;
use App\Models\KitCheck;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class CreateKitCheck extends CreateRecord
{
    protected static string $resource = KitCheckResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Log Kit Check';

    protected static ?string $navigationLabel = 'Log';

    protected static ?string $breadcrumb = 'Log';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('checked_on')
                ->default(Carbon::now())
                ->required(),
            Forms\Components\Select::make('checked_by_id')
                ->relationship(
                    name: 'checked_by', titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query
                        ->whereRaw('FIND_IN_SET(?, accreditations)', [Accreditation::KitChecker->value])
                        ->orWhere('role', Role::TeamLeader)
                )
                ->default(fn (Request $request) => $request->user()->id)
                ->preload()
                ->searchable(),
            Forms\Components\Textarea::make('comment')
                ->columnSpanFull(),
            Forms\Components\Select::make('user_ids')
                ->label('Users')
                ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                ->multiple()
                ->required()
                ->preload()
                ->searchable(),
        ]);
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Log')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function handleRecordCreation(array $data): KitCheck
    {
        $attributes = Arr::except($data, 'user_ids');
        $user_ids = $data['user_ids'];

        if (! is_array($user_ids)) {
            $user_ids = [$user_ids];
        }

        foreach ($user_ids as $user_id) {
            $record = new KitCheck([
                ...$attributes,
                'user_id' => $user_id,
            ]);
            $record->save();
        }

        return $record;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Logged';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
