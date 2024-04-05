@use('App\Enums\Accreditation')
@use('App\Models\User')
<x-layout.app :title="__('Users')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-50">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang('Users')
                </h1>

                @can('create', App\Models\User::class)
                    <nav>
                        <x-button.primary :href="route('user.create')">@lang('Add User')</x-button.primary>
                    </nav>
                @endcan
            </div>
        </header>

        <div>
            @foreach ($users as $user)
                <div class="py-2 px-4 sm:px-8 border-b hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    @click="window.location='{{ route('user.show', $user) }}'">
                    <h2 class="text-lg font-medium"><a href="{{ route('user.show', $user) }}">{{ $user->name }}</a></h2>

                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @unless ($user->isActive())
                            <x-badge.active :active="$user->isActive()" class="text-sm text-nowrap whitespace-nowrap" />
                        @endunless
                        <x-badge.role :role="$user->role" class="text-sm text-nowrap whitespace-nowrap" />
                        @if ($user->isUnder18() || $user->isParent())
                            <x-badge.section :section="$user->section" class="text-sm text-nowrap whitespace-nowrap" />
                        @endif
                        @if ($user->isPermitHolder())
                            <a href="{{ route('user.qualification.index', $user) }}">
                                <x-badge.permit-holder class="text-sm" />
                            </a>
                        @endif
                        @if ($user->isKeyHolder())
                            <x-badge.key-holder label="" class="text-sm text-nowrap whitespace-nowrap" />
                        @endif
                        @foreach ($user->accreditations as $accreditation)
                            <x-badge.accreditation :accreditation="$accreditation" class="text-sm text-nowrap whitespace-nowrap" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </section>
</x-layout.app>
