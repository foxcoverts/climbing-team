@props(['comment', 'change'])
<div class="border-l-2 ml-2 pl-2" id="{{ $comment->id }}" x-data="{ menu: false, editing: false, deleting: false, submitted: false }">
    <div>
        @include('change.partials.attendee-link', [
            'booking' => $change->booking,
            'attendee' => $change->author,
        ])
        {{ __('commented') }}
        @include($booking_link, [
            'booking' => $change->booking,
            'show' => 'link',
            'before' => __('on '),
            'after' => ':',
        ])
    </div>
    <div class="border-l-2 ml-2 pl-2 flex gap-2" x-show="!editing">
        <p>{{ $comment->body }}
            @if ($comment->created_at != $comment->updated_at)
                <em class="not-italic text-sm text-gray-600 dark:text-gray-400">Edited</em>
            @endif
        </p>
        @canany(['update', 'delete'], $comment)
            <div class="relative">
                <button x-show="!editing && !deleting" class="hover:bg-gray-200 dark:hover:bg-gray-700 p-1"
                    :class="{
                        'rounded-xl border border-transparent': !menu,
                        'rounded-t-xl border border-b-0 bg-gray-100 border-gray-300 dark:border-gray-400 dark:bg-gray-900': menu,
                    }"
                    @click="menu = !menu"><x-icon.more class="w-4 h-4 fill-current transition-transform"
                        ::class="{ 'rotate-90': menu }" /></button>
                <div x-cloak x-show="menu" @click.outside="menu = false"
                    class="absolute right-0 -mt-1 z-40 text-gray-900 bg-gray-100 dark:text-gray-100 dark:bg-gray-900 shadow-sm dark:shadow-gray-400 border border-gray-300 dark:border-gray-400 divide-y divide-gray-200 dark:divide-gray-700">
                    @can('update', $comment)
                        <button type="button" @click="editing = true; menu = false"
                            class="whitespace-nowrap block hover:bg-gray-200 dark:hover:bg-gray-700 py-1 px-2 w-full text-left">{{ __('Edit Comment') }}</button>
                    @endcan
                    @can('delete', $comment)
                        <button type="button" @click="deleting = true; menu = false"
                            class="whitespace-nowrap block hover:bg-gray-200 dark:hover:bg-gray-700 py-1 px-2 w-full text-left">{{ __('Delete Comment') }}</button>
                    @endcan
                </div>
            </div>
        @endcanany
    </div>
    @can('update', $comment)
        <div x-cloak x-show="editing" x-trap="editing" @keyup.escape="editing = false">
            <form method="POST" action="{{ route('comment.update', $comment) }}"
                @submit="setTimeout(() => submitted = true, 0)">
                @csrf
                @method('PUT')
                <div class="flex items-stretch mt-1">
                    <x-text-input id="body" name="body" required :value="$comment->body"
                        class="flex-grow flex-shrink rounded-r-none" x-bind:readonly="submitted" />
                    <x-button.primary class="rounded-l-none" x-bind:disabled="submitted">
                        <x-icon.save class="w-4 h-4 fill-current" x-show="!submitted" />
                        <x-icon.loading class="w-4 h-4 fill-gray-400 dark:fill-gray-600 animate-spin" fill="none" x-cloak
                            x-show="submitted" />
                    </x-button.primary>
                </div>
            </form>
        </div>
    @endcan
    @can('delete', $comment)
        <div x-cloak x-show="deleting" x-trap="deleting" @keyup.escape="deleting = false">
            <form method="POST" action="{{ route('comment.destroy', $comment) }}"
                @submit="setTimeout(() => submitted = true, 0)">
                @csrf
                @method('DELETE')
                <p class="inline-flex">{{ __('Delete this comment?') }}</p>
                <x-button.danger x-bind:disabled="submitted" :label="__('Yes, delete')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Yes, delete') }}'" />
                <x-button.secondary x-show="!submitted" @click="deleting = false" :label="__('Cancel')" />
            </form>
        </div>
    @endcan
</div>
