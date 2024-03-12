<?php

namespace App\Http\Controllers\Api;

use App\Actions\RespondToBookingAction;
use App\Http\Controllers\Controller;
use App\Models\MailLog;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class StoreMailLogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request)
    {
        try {
            $mail = new MailLog([
                'body' => $request->getContent(),
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($mail->calendar?->getMethod() == 'REPLY') {
            foreach ($mail->calendar->getEvents() as $event) {
                if (!$event->getBooking()) continue;

                try {
                    $respondToBooking = new RespondToBookingAction($event->getBooking());
                } catch (InvalidArgumentException $e) {
                    continue;
                }

                foreach ($event->getAttendees() as $attendee) {
                    if (!$attendee->getUser()) continue;

                    if ($change = $respondToBooking(
                        $attendee->getUser(),
                        $attendee->getStatus(),
                        $attendee->getComment()
                    )) {
                        $change->created_at = $event->getSentAt();
                        $change->save();
                    }
                }
            }
            $mail->done = true;
        }
        $mail->save();

        return response()->json("ok", Response::HTTP_OK);
    }
}
