@use('App\Enums\GirlguidingScheme')
@use('App\Enums\MountainTrainingAward')
@use('App\Enums\ScoutPermitActivity')
@use('App\Enums\ScoutPermitCategory')
@use('App\Enums\ScoutPermitType')
<x-layout.app :title="__('Add Qualification')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add Qualification') }}
                </h1>
            </div>
        </header>

        <div>
            <h2 class="py-2 px-4 sm:px-8 text-xl font-medium text-grey-900 dark:text-grey-100">
                {{ __('Which user would you like to give a qualification?') }}</h2>

            <ul class="divide-y border-y">
                @foreach ($users as $user)
                    <li><a href="{{ route('user.qualification.create', $user) }}"
                            class="block py-2 px-4 sm:px-8 hover:bg-gray-50">{{ $user->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <footer class="p-4 sm:px-8 flex flex-wrap items-center gap-4">
            @can('viewAny', [App\Models\Qualification::class])
                <x-button.secondary :href="route('qualification.index')" :label="__('Back')" />
            @endcan
        </footer>
    </section>
</x-layout.app>
