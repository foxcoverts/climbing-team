<x-layout.app :title="__('Booking')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8">
            @include('booking.partials.details')
        </div>

        <footer class="p-4 sm:px-8 flex items-start gap-4">
            @can('restore', $booking)
                <form method="POST" action="{{ route('trash.booking.update', $booking) }}" x-data="{ submitted: false }"
                    x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="deleted_at" value="0" />
                    <x-button.primary x-bind:disabled="submitted" :label="__('Restore')"
                        x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Restore') }}'" />
                </form>
            @endcan
            @include('booking.partials.force-delete-button')
            @can('viewTrashed', App\Models\Booking::class)
                <x-button.secondary :href="route('trash.booking.index')" :label="__('Back')" />
            @endcan
        </footer>
    </section>
</x-layout.app>
