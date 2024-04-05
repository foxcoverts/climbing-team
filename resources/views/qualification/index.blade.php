@use('Carbon\Carbon')
<x-layout.app :title="__('Qualifications') . ' - ' . $user->name">
    <section class="py-4" x-data="{ showExpired: false }">
        <header class="px-4 sm:px-8">
            <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                {{ $user->name }}
            </h1>
        </header>

        <div class="sm:mx-8 my-4 text-gray-700 dark:text-gray-300 divide-y border-b sm:hidden">
            <h2
                class="px-3 py-2 text-left text-nowrap font-medium sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                @lang('Qualifications')</h2>
            @forelse ($qualifications as $qualification)
                <div class="px-3 py-2" x-data="{{ Js::from(['isExpired' => $qualification->isExpired()]) }}" x-cloak x-show="!isExpired || showExpired" x-transition>
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
                <p class="px-3 py-2">@lang('This user has no qualifications.')</p>
            @endforelse
        </div>

        <div class="sm:mx-8 min-w-lg hidden sm:block">
            <table class="w-full mt-6 text-gray-700 dark:text-gray-300">
                <thead>
                    <tr>
                        <th
                            class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                            @lang('Qualification')</th>
                        <th
                            class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                            @lang('Type')</th>
                        <th
                            class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                            @lang('Expires')
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 border-y border-gray-200">
                    @forelse ($qualifications as $qualification)
                        <tr class="hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                            @click="window.location='{{ route('user.qualification.show', [$user, $qualification]) }}'"
                            x-data="{{ Js::from(['isExpired' => $qualification->isExpired()]) }}" x-cloak x-show="!isExpired || showExpired" x-transition>
                            <td class="px-3 py-2">
                                <a
                                    href="{{ route('user.qualification.show', [$user, $qualification]) }}">{{ $qualification->detail->summary }}</a>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap align-top">
                                @lang('app.qualification.type.' . $qualification->detail_type)
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap align-top">
                                @if ($qualification->expires_on)
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
                                @else
                                    <em>@lang('unlimited')</em>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-2">
                                @lang('This user has no qualifications.')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="flex flex-wrap items-start gap-4 mt-6 px-4 sm:px-8">
            @can('create', [App\Models\Qualification::class, $user])
                <x-button.primary :href="route('user.qualification.create', $user)">
                    @lang('Add')
                </x-button.primary>
            @endcan
            @can('view', $user)
                <x-button.secondary :href="route('user.show', $user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
            <div class="py-1">
                <label class="flex items-center gap-1 cursor-pointer">
                    <input type="checkbox" name="__show_expired" x-model="showExpired" />
                    <span>@lang('Show expired')</span>
                </label>
            </div>
        </footer>
    </section>
</x-layout.app>
