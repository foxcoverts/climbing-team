<x-layout.app :title="__('Booking')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')
        @include('booking.partials.details')

        <footer class="mt-6 flex items-start gap-4">
            @can('restore', $booking)
                <form method="POST" action="{{ route('trash.booking.update', $booking) }}" id="restore">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="deleted_at" value="0" />
                    <x-button.primary form="restore">
                        {{ __('Restore') }}
                    </x-button.primary>
                </form>
            @endcan
            @include('booking.partials.force-delete-button')
            <x-button.secondary :href="route('trash.booking.index')">
                {{ __('Back') }}
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
