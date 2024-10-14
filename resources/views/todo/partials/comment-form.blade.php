@can('comment', $todo)
    <form method="POST" action="{{ route('comment.store') }}" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $todo->id }}" autocomplete="off" />
        <input type="hidden" name="parent_type" value="{{ $todo::class }}" autocomplete="off" />

        <x-input-label for="body" :value="__('Add Comment')" />
        <div class="flex items-stretch mt-1">
            <x-text-input id="body" name="body" required value=""
                class="flex-grow flex-shrink rounded-r-none" />
            <x-button.primary class="rounded-l-none" x-bind:disabled="submitted">
                <x-icon.send class="w-4 h-4 fill-current" x-show="!submitted" />
                <x-icon.loading class="w-4 h-4 fill-gray-400 dark:fill-gray-600 animate-spin" fill="none" x-cloak
                    x-show="submitted" />
            </x-button.primary>
        </div>
    </form>
@endcan
