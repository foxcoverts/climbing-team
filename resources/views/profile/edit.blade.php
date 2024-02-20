<x-layout.app :title="__('Profile')">
    <div class="divide-y">
        <div class="p-4 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="p-4 sm:p-8">
            @include('profile.partials.update-password-form')

        </div>
        <div class="p-4 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-layout.app>
