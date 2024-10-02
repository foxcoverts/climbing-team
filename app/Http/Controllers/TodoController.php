<?php

namespace App\Http\Controllers;

use App\Enums\TodoStatus;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class TodoController extends Controller
{
    /**
     * Display a listing of the todos.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Todo::class);

        $statuses = collect($request->get('status'))
            ->map(fn ($value) => TodoStatus::tryFrom($value))
            ->filter()
            ->whenEmpty(function (Collection $collection): Collection {
                return $collection->push(TodoStatus::NeedsAction, TodoStatus::InProcess);
            });

        return view('todo.index', [
            'todos' => Todo::withStatus($statuses->all())
                ->ordered()
                ->get(),
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new todo.
     */
    public function create(): View
    {
        Gate::authorize('create', Todo::class);

        return view('todo.create', [
            'todo' => new Todo,
        ]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreTodoRequest $request): RedirectResponse
    {
        Gate::authorize('create', Todo::class);

        $todo = Todo::create($request->validated());

        return redirect()->route('todo.index')
            ->withFragment($todo->id)
            ->with('alert.message', __('Task created'));
    }

    /**
     * Display the specified todo.
     */
    public function show(Todo $todo): View
    {
        Gate::authorize('view', $todo);

        return view('todo.show', [
            'todo' => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified todo.
     */
    public function edit(Todo $todo): View
    {
        Gate::authorize('update', $todo);

        return view('todo.edit', [
            'todo' => $todo,
        ]);
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        Gate::authorize('complete', $todo);

        $alertMessage = __('Task updated.');

        if (Gate::check('update')) {
            $validated = $request->validated();
        } else {
            $validated = $request->safe()->only('status');
        }
        $todo->fill($validated);

        if ($todo->isDirty('status')) {
            switch ($todo->status) {
                case TodoStatus::NeedsAction:
                    $todo->started_at = null;
                    $todo->completed_at = null;
                    $alertMessage = __('Task reset.');
                    break;

                case TodoStatus::InProcess:
                    $todo->started_at ??= $todo->freshTimestamp();
                    $todo->completed_at = null;
                    $alertMessage = __('Task started.');
                    break;

                case TodoStatus::Completed:
                    $todo->completed_at = $todo->freshTimestamp();
                    $alertMessage = __('Task completed.');
                    break;

                case TodoStatus::Cancelled:
                    $todo->completed_at = $todo->freshTimestamp();
                    $alertMessage = __('Task cancelled.');
                    break;
            }
        }
        $todo->save();

        return redirect()->route('todo.index')
            ->withFragment($todo->id)
            ->with('alert.message', $alertMessage);
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Todo $todo)
    {
        Gate::authorize('delete', $todo);

        $todo->delete();

        return redirect()->route('todo.index')
            ->with('alert.message', __('Task removed.'));
    }
}
