@use('Carbon\Carbon')
<x-layout.app :title="__('Qualification - :name', ['name' => $user->name])">
    <section class="p-4 sm:px-8 max-w-xl space-y-4">
        <header>
            <h1 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                {{ $user->name }}
            </h1>
        </header>

        <article class="space-y-2">
            <h2 class="text-lg sm:text-xl font-medium text-gray-900 dark:text-gray-100">@lang('app.qualification.type.' . $qualification->detail_type)</h2>

            <div>
                <x-fake-label :value="__('Activity')" />
                <p class="text-gray-700 dark:text-gray-300">@lang('app.scout-permit.activity.' . $qualification->detail->activity->value)</p>
            </div>

            <div>
                <x-fake-label :value="__('Category')" />
                <p class="text-gray-700 dark:text-gray-300">@lang('app.scout-permit.category.' . $qualification->detail->category->value)</p>
            </div>

            <div>
                <x-fake-label :value="__('Permit Type')" />
                <p class="text-gray-700 dark:text-gray-300">@lang('app.scout-permit.permit-type.' . $qualification->detail->permit_type->value)</p>
            </div>

            <div>
                <x-fake-label :value="__('Restrictions')" />
                <p class="text-gray-700 dark:text-gray-300">
                    @empty($qualification->detail->restrictions)
                        <em>@lang('None')</em>
                    @else
                        {{ $qualification->detail->restrictions }}
                    @endempty
                </p>
            </div>

            <div>
                <x-fake-label :value="$qualification->expires_on->endOfDay()->isPast() ? __('Expired') : __('Expires')" />
                <p><span @class([
                    'cursor-default',
                    'text-red-500' => $qualification->isExpired(),
                ])
                        title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                        @if ($qualification->expires_on->isToday())
                            @lang('today')
                        @else
                            {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                        @endif
                    </span></p>
            </div>
        </article>

        <footer class="flex items-start gap-4 mt-6">
            @can('update', [$user, $qualification])
                <x-button.primary :href="route('user.qualification.edit', [$user, $qualification])">
                    @lang('Edit')
                </x-button.primary>
            @endcan
            @can('manage', App\Models\Qualification::class)
                <x-button.secondary :href="route('user.qualification.index', $user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
