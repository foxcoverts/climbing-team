<x-layout.app :title="__('Booking')">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')
        @include('booking.partials.details')

        <footer class="mt-6 flex items-start gap-4">
            @can('restore', $booking)
                <form method="POST" action="{{ route('trash.booking.update', $booking) }}" x-data="{ submitted: false }"
                    x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="deleted_at" value="0" />
                    <x-button.primary x-bind:disabled="submitted"
                        x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Restore') }}'" />
                </form>
            @endcan
            @include('booking.partials.force-delete-button')
            <x-button.secondary :href="route('trash.booking.index')">
                @lang('Back')
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
