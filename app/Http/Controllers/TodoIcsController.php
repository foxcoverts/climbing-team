<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TodoIcsController extends Controller
{
    /**
     * Display an iCal listing for the specified resource.
     */
    public function show(Request $request, Todo $todo): Response
    {
        Gate::authorize('view', $todo);

        return $this->ics(
            [$todo],
            $request->user(),
            title: __(':Name Tasks', ['name' => config('app.name', 'Fox Coverts Climbing')]),
            filename: sprintf('todo-%s', $todo->id),
            debug: config('app.debug') && $request->get('debug'),
        );
    }

    /**
     * Turn tasks into an ICS file.
     *
     * @param  array<Todo>  $todos
     */
    protected function ics($todos, User $user, string $title, string $description = '', string $filename = 'todo', bool $debug = false): Response
    {
        $ics = response()->view('todo.ics', [
            'todos' => $todos,
            'user' => $user,
            'name' => $title,
            'description' => $description,
            'refreshInterval' => DateInterval::createFromDateString('7 days'),
        ]);

        if ($debug) {
            return $ics
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Content-Disposition', 'inline');
        } else {
            return $ics
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', sprintf('inline; filename="%s.ics"', $filename));
        }
    }
}
