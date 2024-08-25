<?php

namespace App\Http\Controllers;

use App\Enums\TodoStatus;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class TodoController extends Controller
{
    /**
     * Display a listing of the todos.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Todo::class);

        return view('todo.index', [
            'todos' => Todo::orderBy('status')
                ->orderBy('priority')
                ->orderByRaw('-due_at DESC')
                ->get(),
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
        Gate::authorize('update', $todo);
        $alertMessage = __('Task updated.');

        $todo->fill($request->validated());
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
