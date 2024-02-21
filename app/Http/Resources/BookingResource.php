<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attendee = $this->attendees()->find($request->user());

        return [
            'id' => $this->id,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status,
            'activity' => $this->activity,
            'location' => $this->location,
            'group_name' => $this->group_name,
            'notes' => Str::markdown("$this->notes", [
                'renderer' => [
                    'block_separator' => "\n",
                    'inner_separator' => "\n",
                    'soft_break' => '<br \\>',
                ],
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
            'attendance' => $attendee?->attendance->status,
            'url' => route('booking.show', $this),
        ];
    }
}
