<x-layout.app :title="__('Booking')">
    <section class="p-4 sm:p-8 max-w-xl">
        @include('booking.partials.header', ['booking' => $booking])
        @include('booking.partials.details', ['booking' => $booking])

        <form method="POST" action="{{ route('trash.booking.update', $booking) }}" id="restore">
            @csrf
            @method('PATCH')
            <input type="hidden" name="deleted_at" value="0" />
        </form>

        <div class="mt-6 flex items-center gap-4">
            @can('restore', $booking)
                <x-button.primary form="restore">
                    {{ __('Restore') }}
                </x-button.primary>
            @endcan
            @include('booking.partials.force-delete-button')
            <x-button.secondary :href="route('trash.booking.index')">
                {{ __('Back') }}
            </x-button.secondary>
        </div>
    </section>
</x-layout.app>
