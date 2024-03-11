<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Change\Comment;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorize('comment', $booking);
        $request->validate([
            'body' => ['required', 'string'],
        ]);

        $change = new Change;
        $change->author()->associate($request->user());
        $change->booking()->associate($booking);
        $change->push();

        $comment = new Comment;
        $comment->body = $request->body;
        $change->comments()->save($comment);

        return redirect()->route('booking.show', $booking)->withFragment('#' . $change->id);
    }
}
