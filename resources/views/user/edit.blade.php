<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Profile Information') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Update this User's profile information.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('user.update', $user) }}" class="mt-6 space-y-6">
                            @method('PATCH')
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $user->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    :value="old('email', $user->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="text-sm mt-2 text-orange-800 dark:text-orange-200">
                                        {{ __('This email address is unverified.') }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <x-button.primary>
                                    {{ __('Update') }}
                                </x-button.primary>

                                <x-button.secondary :href="route('user.show', $user)">
                                    {{ __('Cancel') }}
                                </x-button.secondary>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
