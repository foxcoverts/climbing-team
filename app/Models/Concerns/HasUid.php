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

    public static function findByUid(string $uid): static|null
    {
        $parts = preg_split('/[+@]/', $uid);

        if ($parts[0] != Str::singular((new static)->getTable())) {
            return null;
        }

        return static::find($parts[1]);
    }
}
