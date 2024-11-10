@use('Carbon\Carbon')
@props(['currentUser', 'user' => null, 'qualifications'])
<x-layout.app :title="__('Qualifications')">
    <section x-data="{ showExpired: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.qualification style="height: .75lh" class="fill-current" aria-hidden="true" />
                    @if (!isset($user) || empty($user))
                        <span>{{ __('Qualifications') }}</span>
                    @elseif ($user->is($currentUser))
                        <span>{{ __('My Qualifications') }}</span>
                    @else
                        <span>{{ __(':Name - Qualifications', ['name' => $user->name]) }}</span>
                    @endif
                </h1>

                <nav class="flex items-center gap-4 justify-end grow">
                    @if ($qualifications->contains(fn($qualification) => $qualification->isExpired()))
                        <label class="block cursor-pointer">
                            <x-input-checkbox name="__show_expired" x-model="showExpired" />
                            {{ __('Show expired') }}
                        </label>
                    @endif

                    @can('create', [App\Models\Qualification::class, $user])
                        @if (empty($user))
                            <x-button.primary :href="route('qualification.create')" :label="__('Add')" />
                        @else
                            <x-button.primary :href="route('user.qualification.create', $user)" :label="__('Add')" />
                        @endif
                    @endcan
                </nav>
            </div>
        </header>

        <div class="text-gray-700 dark:text-gray-300">
            @forelse ($qualifications as $qualification)
                <div class="py-2 px-4 sm:px-8 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 border-b cursor-pointer"
                    x-data="{{ Js::from(['isExpired' => $qualification->isExpired()]) }}" x-cloak x-show="!isExpired || showExpired" x-transition
                    @click="window.location={{ Js::from(route('qualification.show', $qualification)) }}">
                    <h3 class="font-medium"><a
                            href="{{ route('qualification.show', $qualification) }}">{{ $qualification->detail->summary }}</a>
                    </h3>
                    <p><dfn class="not-italic font-medium">{{ __('Type') }}:</dfn>
                        {{ __('app.qualification.type.' . $qualification->detail_type) }}</p>
                    @if ($qualification->expires_on)
                        <p><dfn class="not-italic font-medium">
                                @if ($qualification->isExpired())
                                    {{ __('Expired') }}
                                @else
                                    {{ __('Expires') }}
                                @endif
                            </dfn>

                            <span @class([
                                'cursor-default',
                                'text-red-500' => $qualification->isExpired(),
                            ])
                                title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                                @if ($qualification->expires_on->isToday())
                                    {{ __('today') }}
                                @else
                                    {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                                @endif
                            </span>
                        </p>
                    @endif
                    @if (empty($user))
                        <p><dfn class="not-italic font-medium">{{ __('User') }}:</dfn>
                            {{ $qualification->user->name }}</p>
                    @endif
                </div>
            @empty
                <p class="p-4 sm:px-8">{{ __('This user has no qualifications.') }}</p>
            @endforelse
        </div>
    </section>
</x-layout.app>
