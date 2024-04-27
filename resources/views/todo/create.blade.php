<x-layout.app :title="__('Add Task')">
    <section x-data="{ submitted: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang('Create Task')
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('todo.store') }}" id="create-todo" class="p-4 sm:px-8" x-data="{
            todo: {{ Js::from([
                'summary' => old('summary', ''),
                'description' => old('description', null),
                'location' => old('location', 'Fox Coverts Campsite'),
                'priority' => old('priority', 5),
                'status' => old('status', TodoStatus::class),
                'due_at' => old('due_at', null),
            ]) }},
        }"
            x-on:submit="setTimeout(() => submitted = 'create-todo', 0)">
            @csrf

            <div class="space-y-6 max-w-prose">
                <div>
                    <x-input-label for="summary" :value="__('Summary')" />
                    <x-text-input id="summary" name="summary" class="mt-1 w-full" required x-model="todo.summary"
                        :placeholder="__('New To-Do')" />
                    <x-input-error class="mt-2" :messages="$errors->get('summary')" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Notes')" />
                    <x-textarea id="description" name="description" class="mt-1 w-full" x-model="todo.description"
                        x-meta-enter.prevent="$el.form.requestSubmit()" />
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" name="location" class="mt-1 w-full" required x-model="todo.location" />
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>

                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <x-select-input id="priority" name="priority" required x-model="todo.priority">
                        <x-select-input.enum :options="App\Enums\TodoPriority::class" lang="app.todo.priority.:value" />
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                </div>

                <div>
                    <x-input-label for="due_at" :value="__('Due')" />
                    <x-text-input id="due_at" name="due_at" type="datetime-local" x-model="todo.due_at"
                        class="mt-1" />
                    <x-input-error class="mt-2" :messages="$errors->get('due_at')" />
                </div>
            </div>
        </form>

        <footer class="pb-4 px-4 sm:px-8 flex flex-wrap items-center gap-4">
            <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted == 'create-todo' ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'"
                form="create-todo" />

            @can('viewAny', App\Models\Todo::class)
                <x-button.secondary :href="route('todo.index')">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
