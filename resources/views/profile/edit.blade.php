<x-layout.app :title="__('Profile')">
    <div class="divide-y">
        <div class="p-4 sm:px-8">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="p-4 sm:px-8">
            @include('profile.partials.update-password-form')
        </div>
        @can('delete', auth()->user())
            <div class="p-4 sm:px-8">
                @include('profile.partials.delete-user-form')
            </div>
        @endcan
    </div>
</x-layout.app>
