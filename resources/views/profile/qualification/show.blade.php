@use('Carbon\Carbon')
<x-layout.app :title="__('Qualification')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.qualification style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span>{{ __('My Qualifications') }}</span>
                </h1>
            </div>
        </header>

        <article class="mt-4 px-4 sm:px-8 space-y-2 max-w-prose">
            <h2 class="text-lg sm:text-xl font-medium text-gray-900 dark:text-gray-100">
                {{ __('app.qualification.type.' . $qualification->detail_type) }}</h2>

            @if ($qualification->detail instanceof \App\Models\GirlguidingQualification)
                <div>
                    <x-fake-label :value="__('Scheme')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __(':scheme - Level :level', [
                            'scheme' => __('app.girlguiding.scheme.' . $qualification->detail->scheme->value),
                            'level' => $qualification->detail->level,
                        ]) }}
                    </p>
                </div>
            @elseif ($qualification->detail instanceof \App\Models\MountainTrainingQualification)
                <div>
                    <x-fake-label :value="__('Award')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __('app.mountain-training.award.' . $qualification->detail->award->value) }}</p>
                </div>
            @elseif ($qualification->detail instanceof \App\Models\ScoutPermit)
                <div>
                    <x-fake-label :value="__('Activity')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __('app.scout-permit.activity.' . $qualification->detail->activity->value) }}</p>
                </div>

                <div>
                    <x-fake-label :value="__('Category')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __('app.scout-permit.category.' . $qualification->detail->category->value) }}</p>
                </div>

                <div>
                    <x-fake-label :value="__('Permit Type')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __('app.scout-permit.permit-type.' . $qualification->detail->permit_type->value) }}</p>
                </div>

                <div>
                    <x-fake-label :value="__('Restrictions')" />
                    <p class="text-gray-700 dark:text-gray-300">
                        @if (empty($qualification->detail->restrictions))
                            <em>{{ __('None') }}</em>
                        @else
                            {{ $qualification->detail->restrictions }}
                        @endif
                    </p>
                </div>
            @endif

            @if ($qualification->expires_on)
                <div>
                    <x-fake-label :value="$qualification->expires_on->endOfDay()?->isPast() ? __('Expired') : __('Expires')" />
                    <p><span @class([
                        'cursor-default',
                        'text-red-500' => $qualification->isExpired(),
                    ])
                            title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                            @if ($qualification->expires_on->isToday())
                                {{ __('today') }}
                            @else
                                {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                            @endif
                        </span></p>
                </div>
            @endif
        </article>

        <footer class="p-4 sm:px-8 mt-4 flex flex-wrap items-start gap-4">
            <x-button.secondary :href="route('profile.qualification.index')" :label="__('Back')" />
        </footer>
    </section>
</x-layout.app>
