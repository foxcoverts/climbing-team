<x-layout.app :title="__('booking.title')">
    <section class="p-4 sm:p-8 max-w-xl">
        @include('booking.partials.header', ['booking' => $booking])
        @include('booking.partials.details', ['booking' => $booking])

        <div class="mt-6 flex items-center gap-4">
            <form method="POST" action="{{ route('trash.booking.show', $booking) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="deleted_at" value="0" />
                <x-button.primary>
                    {{ __('Restore') }}
                </x-button.primary>
            </form>
            @include('trash.booking.partials.delete-button')
        </div>
    </section>
</x-layout.app>
