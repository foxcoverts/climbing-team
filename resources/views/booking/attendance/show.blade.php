<x-layout.app :title="__('Edit Attendance')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')
        <div class="md:flex md:space-x-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')

                <footer class="flex items-center gap-4 mt-4">
                    @include('booking.partials.respond-button')

                    <x-button.secondary :href="route('booking.invite')">
                        {{ __('Back') }}
                    </x-button.secondary>
                </footer>
            </div>
            @include('booking.partials.guest-list', ['showTools' => false])
        </div>
    </section>
</x-layout.app>
