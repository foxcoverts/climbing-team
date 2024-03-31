@use('App\Enums\Accreditation')
@use('App\Models\User')
<x-layout.app :title="__('Users')">
    <section class="p-4 sm:px-8">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Users')
            </h2>
        </header>

        <table class="w-full mt-6 text-gray-700 dark:text-gray-300 ">
            <thead>
                <tr>
                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 w-full">
                        @lang('Name')</th>
                    <th
                        class="px-3 py-2 text-center text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 hidden sm:table-cell">
                        @lang('Active')</th>
                    <th
                        class="px-3 py-2 text-center text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 hidden sm:table-cell">
                        @lang('Section')
                    <th
                        class="px-3 py-2 text-center text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('Role')</th>
                    <th
                        class="px-3 py-2 text-center text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 hidden sm:table-cell">
                        @lang('Permits')</th>
                    <th
                        class="px-3 py-2 text-center text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 hidden xl:table-cell">
                        @lang('Manages')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-y border-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                        @click="window.location='{{ route('user.show', $user) }}'">
                        <td class="px-3 py-2">
                            <a href="{{ route('user.show', $user) }}">{{ $user->name }}</a>
                        </td>
                        <td class="px-1 text-center hidden sm:table-cell">
                            <x-badge.active :active="$user->isActive()" class="text-sm text-nowrap whitespace-nowrap" />
                        </td>
                        <td class="px-1 text-center hidden sm:table-cell">
                            @if ($user->isUnder18() || $user->isParent())
                                <x-badge.section :section="$user->section" class="text-sm text-nowrap whitespace-nowrap" />
                            @endif
                        </td>
                        <td class="px-1 text-center">
                            <x-badge.role :role="$user->role" class="text-sm text-nowrap whitespace-nowrap" />
                        </td>
                        <td class="px-1 text-center hidden sm:table-cell">
                            @if ($user->isPermitHolder())
                                <x-badge.permit-holder class="text-sm text-nowrap whitespace-nowrap" />
                            @endif
                        </td>
                        <td class="px-1 text-center hidden xl:table-cell">
                            <div class="flex items-center justify-center gap-1">
                                @foreach (Accreditation::cases() as $accreditation)
                                    @if ($user->accreditations->contains($accreditation))
                                        <x-badge.accreditation :accreditation="$accreditation"
                                            class="text-sm text-nowrap whitespace-nowrap" />
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</x-layout.app>
