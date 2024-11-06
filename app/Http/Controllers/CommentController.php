<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Models\Booking;
use App\Models\Change;
use App\Models\ChangeComment;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_type' => ['required', 'string', Rule::in([Booking::class, Todo::class])],
            'parent_id' => ['required', 'ulid'],
            'body' => ['required', 'string'],
        ]);

        $parent_type = $request->input('parent_type');
        $parent = $parent_type::find($request->input('parent_id'));

        Gate::authorize('comment', $parent);

        $change = new Change;
        $change->author()->associate($request->user());
        $change->changeable()->associate($parent);
        $change->push();

        $comment = new ChangeComment;
        $comment->body = $request->body;
        $change->comments()->save($comment);

        event(new CommentCreated($parent, $comment));

        return $this->redirectFor($parent)->withFragment('#'.$change->id);
    }

    public function update(Request $request, ChangeComment $comment)
    {
        Gate::authorize('update', $comment);

        $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment->body = $request->body;
        $comment->save();

        $comment->change->touch();

        return $this->redirectFor($comment->change->changeable)
            ->with('alert.info', __('Comment saved.'));
    }

    public function destroy(ChangeComment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->change->delete();

        return $this->redirectFor($comment->change->changeable)
            ->with('alert.info', __('Comment deleted.'));
    }

    protected function redirectFor(Model $parent): RedirectResponse
    {
        switch ($parent::class) {
            case Booking::class:
                return redirect()->route('booking.show', $parent);
        }

        return redirect()->back();
    }
}
