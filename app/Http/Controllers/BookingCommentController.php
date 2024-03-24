<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\ChangeComment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        Gate::authorize('comment', $booking);

        $request->validate([
            'body' => ['required', 'string'],
        ]);

        $change = new Change;
        $change->author()->associate($request->user());
        $change->booking()->associate($booking);
        $change->push();

        $comment = new ChangeComment;
        $comment->body = $request->body;
        $change->comments()->save($comment);

        return redirect()->route('booking.show', $booking)->withFragment('#' . $change->id);
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

        return redirect()->route('booking.show', $comment->change->booking)
            ->with('alert.info', __('Comment saved.'));
    }

    public function destroy(ChangeComment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->change->delete();

        return redirect()->route('booking.show', $comment->change->booking)
            ->with('alert.info', __('Comment deleted.'));
    }
}
