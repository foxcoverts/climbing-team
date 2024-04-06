<?php

namespace App\Events;

use App\Models\Key;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class KeyTransferred
{
    use SerializesModels;

    public function __construct(
        public Key $key,
        public User $from,
    ) {
    }
}
