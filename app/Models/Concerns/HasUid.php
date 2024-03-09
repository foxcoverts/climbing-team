<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasUid
{
    public function getUidAttribute(): string
    {
        $domain = parse_url(config('app.url'), PHP_URL_HOST);
        if ($domain == 'localhost') {
            $domain = 'climbfoxcoverts.local';
        }
        return sprintf('%s+%s@%s', Str::singular($this->getTable()), $this->id, $domain);
    }
}
