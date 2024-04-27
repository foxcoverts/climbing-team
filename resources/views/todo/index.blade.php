@use('App\Enums\TodoStatus')
<x-layout.app :title="__('Tasks')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang('Tasks')
                </h1>

                @can('create', \App\Models\Todo::class)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('todo.create')" :label="__('Add Task')" />
                    </nav>
                @endcan
            </div>
        </header>

        <ul class="divide-y border-b">
            @foreach ($todos as $todo)
                <li @class([
                    'pl-4 sm:pl-8 py-2 flex items-center gap-2 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-900 dark:hover:text-gray-100',
                    'text-gray-500 dark:text-gray-400' =>
                        $todo->status == TodoStatus::Cancelled,
                ]) id="{{ $todo->id }}">
                    <form action="{{ route('todo.update', $todo) }}" method="POST" class="flex items-center">
                        @csrf
                        @method('PATCH')
                        @switch($todo->status)
                            @case(TodoStatus::Completed)
                                <button name="status" value="{{ TodoStatus::NeedsAction->value }}"
                                    title="{{ __('Reset task') }}">
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
                    <a href="{{ route('todo.show', $todo) }}">{{ $todo->summary }}</a>
                    @if ($todo->priority != 5)
                        <x-badge.todo-priority :priority="$todo->priority" class="text-xs" />
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
</x-layout.app>
