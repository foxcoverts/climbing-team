@use('Carbon\Carbon')
<x-layout.app :title="__('Qualifications') . ' - ' . $user->name">
    <section x-data="{ showExpired: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang(':Name - Qualifications', ['name' => $user->name])
                </h1>

                <nav class="flex items-center gap-4 justify-end grow">
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="checkbox" name="__show_expired" x-model="showExpired" />
                        <span>@lang('Show expired')</span>
                    </label>
                    @can('create', [App\Models\Qualification::class, $user])
                        <x-button.primary :href="route('user.qualification.create', $user)">
                            @lang('Add')
                        </x-button.primary>
                    @endcan
                </nav>
            </div>
        </header>

        <div class="text-gray-700 dark:text-gray-300 divide-y">
            @forelse ($qualifications as $qualification)
                <div class="py-2 px-4 sm:px-8 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    x-data="{{ Js::from(['isExpired' => $qualification->isExpired()]) }}" x-cloak x-show="!isExpired || showExpired" x-transition
                    @click="window.location={{ Js::from(route('user.qualification.show', [$user, $qualification])) }}">
                    <h3 class="font-medium"><a
                            href="{{ route('user.qualification.show', [$user, $qualification]) }}">{{ $qualification->detail->summary }}</a>
                    </h3>
                    <p><dfn class="not-italic font-medium">@lang('Type'):</dfn> @lang('app.qualification.type.' . $qualification->detail_type)</p>
                    @if ($qualification->expires_on)
                        <p><dfn class="not-italic font-medium">
                                @if ($qualification->isExpired())
                                    @lang('Expired')
                                @else
                                    @lang('Expires')
                                @endif
                            </dfn>

                            <span @class([
                                'cursor-default',
                                'text-red-500' => $qualification->isExpired(),
                            ])
                                title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                                @if ($qualification->expires_on->isToday())
                                    @lang('today')
                                @else
                                    {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                                @endif
                            </span>
                        </p>
                    @endif
                </div>
            @empty
                <p class="p-4 sm:px-8">@lang('This user has no qualifications.')</p>
            @endforelse
        </div>
    </section>
</x-layout.app>
