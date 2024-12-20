@use('App\Enums\TodoStatus')
<x-layout.app :title="__('Task')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    @can('complete', $todo)
                        <form action="{{ route('todo.update', $todo) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            @switch($todo->status)
                                @case(TodoStatus::Completed)
                                    <button name="status" value="{{ TodoStatus::NeedsAction->value }}" title="{{ __('Reset task') }}">
                                        <x-icon.outline.checkmark class="w-4 h-4 fill-current" />
                                    </button>
                                @break

                                @case(TodoStatus::Cancelled)
                                    <x-icon.outline.close class="w-4 h-4 fill-current" />
                                @break

                                @case(TodoStatus::InProcess)
                                    <button name="status" value="{{ TodoStatus::Completed->value }}"
                                        title="{{ __('Complete task') }}">
                                        <x-icon.outline.dot class="w-4 h-4 fill-current" />
                                    </button>
                                @break

                                @case(TodoStatus::NeedsAction)
                                    <button name="status" value="{{ TodoStatus::Completed->value }}"
                                        title="{{ __('Complete task') }}">
                                        <x-icon.outline class="w-4 h-4 fill-current" />
                                    </button>
                                @break
                            @endswitch
                        </form>
                    @else
                        @switch($todo->status)
                            @case(TodoStatus::Completed)
                                <x-icon.outline.checkmark class="w-4 h-4 fill-current" />
                            @break

                            @case(TodoStatus::Cancelled)
                                <x-icon.outline.close class="w-4 h-4 fill-current" />
                            @break

                            @case(TodoStatus::InProcess)
                                <x-icon.outline.dot class="w-4 h-4 fill-current" />
                            @break

                            @case(TodoStatus::NeedsAction)
                                <x-icon.outline class="w-4 h-4 fill-current" />
                            @break
                        @endswitch
                    @endcan
                    <span>{{ $todo->summary }}</span>
                </h1>

                @can('update', $todo)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('todo.edit', $todo)" :label="__('Edit')" />
                    </nav>
                @endcan
            </div>
        </header>

        <div class="p-4 sm:px-8 space-y-4">
            <div class="space-y-2 max-w-prose">
                @isset($todo->location)
                    <div>
                        <x-fake-label :value="__('Location')" />
                        <p class="text-gray-700 dark:text-gray-300">{{ $todo->location }}</p>
                    </div>
                @endisset

                @isset($todo->description)
                    <div class="prose dark:prose-invert prose-p:my-2 prose-ul:my-2 prose-ol:my-2 prose-li:my-0">
                        <x-fake-label>
                            {{ __('Notes') }}
                            <x-icon.document class="w-3 h-3 fill-gray-600 inline-block" />
                        </x-fake-label>
                        <x-markdown :text="$todo->description" />
                    </div>
                @endisset

                <div>
                    <x-fake-label :value="__('Priority')" />
                    <x-badge.todo-priority :priority="$todo->priority" />
                </div>

                <div>
                    <x-fake-label :value="__('Status')" />
                    <p><x-badge.todo-status :status="$todo->status" /></p>
                </div>

                @isset($todo->started_at)
                    <div>
                        <x-fake-label :value="__('Started on')" />
                        <p class="text-gray-700 dark:text-gray-300">
                            <span x-data="{{ Js::from(['started_at' => localDate($todo->started_at)]) }}"
                                x-text="dateTimeString(started_at)">{{ localDate($todo->started_at)->toDayDateTimeString() }}</span>
                        </p>
                    </div>
                @endisset

                @isset($todo->due_at)
                    <div>
                        <x-fake-label :value="__('Due')" />
                        <p class="text-gray-700 dark:text-gray-300">
                            <span x-data="{{ Js::from(['due_at' => localDate($todo->due_at)]) }}"
                                x-text="dateTimeString(due_at)">{{ localDate($todo->due_at)->toDayDateTimeString() }}</span>
                            @if ($todo->isOverdue())
                                <x-badge :label="__('Overdue')" color="pink" icon="outline.exclamation" class="text-sm" />
                            @endif
                        </p>
                    </div>
                @endisset

                @isset($todo->completed_at)
                    <div>
                        <x-fake-label :value="__('Completed on')" />
                        <p class="text-gray-700 dark:text-gray-300">
                            <span x-data="{{ Js::from(['completed_at' => localDate($todo->completed_at)]) }}"
                                x-text="dateTimeString(completed_at)">{{ localDate($todo->completed_at)->toDayDateTimeString() }}</span>
                        </p>
                    </div>
                @endisset
            </div>

            @include('todo.partials.recent-activity')
        </div>
    </section>
</x-layout.app>
