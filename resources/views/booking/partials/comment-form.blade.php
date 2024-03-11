@can('comment', $booking)
    <form method="POST" action="{{ route('booking.comment.create', $booking) }}" class="mt-2">
        @csrf

        <x-input-label for="body" :value="__('Add Comment')" />
        <div class="flex items-stretch mt-1">
            <x-text-input id="body" name="body" required value="" class="flex-grow flex-shrink rounded-r-none" />
            <x-button.primary class="rounded-l-none">@lang('Save')</x-button.primary>
        </div>
    </form>
@endcan
