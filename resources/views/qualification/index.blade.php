@use('Carbon\Carbon')
<x-layout.app :title="__('Qualifications') . ' - ' . $user->name">
    <section class="p-4 sm:px-8">
        <header>
            <h1 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                {{ $user->name }}
            </h1>
        </header>

        <table class="w-full mt-6 text-gray-700 dark:text-gray-300 ">
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
                        @click="window.location='{{ route('user.qualification.show', [$user, $qualification]) }}'">
                        <td class="px-3 py-2">
                            {{ $qualification->detail->summary }}
                        </td>
                        <td class="px-3 py-2">
                            <a
                                href="{{ route('user.qualification.show', [$user, $qualification]) }}">@lang('app.qualification.type.' . $qualification->detail_type)</a>
                        </td>
                        <td class="px-3 py-2">
                            @if ($qualification->expires_on)
                                <span class="cursor-default"
                                    title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                                    @if ($qualification->expires_on->isToday())
                                        @lang('today')
                                    @else
                                        {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                                    @endif
                                </span>
                            @else
                                <em>@lang('Unlimited')</em>
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

        <footer class="flex items-start gap-4 mt-6">
            @can('create', [$user, App\Models\Qualification::class])
                <x-button.primary :href="route('user.qualification.create', $user)">
                    @lang('Add')
                </x-button.primary>
            @endcan
            @can('view', $user)
                <x-button.secondary :href="route('user.show', $user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
