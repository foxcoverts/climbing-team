@props(['related', 'booking'])
@can('destroy', [App\Models\Bookable::class, $related, $booking])
    <form method="POST" action="{{ route('booking.related.destroy', [$related, $booking]) }}" class="mr-2">
        @csrf @method('DELETE')
        <x-button.danger :title="__('Remove Related Booking')">
            <x-icon.trash class="h-4 w-4 fill-current" />
        </x-button.danger>
    </form>
@endcan
