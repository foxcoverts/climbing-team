<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TrashedBookingResource extends BookingResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = parent::toArray($request);
        Arr::set($resource, 'url', route('trash.booking.show', $this));
        return $resource;
    }
}
