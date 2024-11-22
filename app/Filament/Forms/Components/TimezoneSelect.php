<?php

namespace App\Filament\Forms\Components;

use Carbon\CarbonTimeZone;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Illuminate\Support\Arr;

class TimezoneSelect extends Select
{
    public function setUp(): void
    {
        $timezones = collect(CarbonTimeZone::listIdentifiers())
            ->mapWithKeys(fn ($identifier) => $this->formatTimezone($identifier))
            ->groupBy(fn ($label, string $timezone) => Arr::first(explode('/', (new CarbonTimeZone($timezone))->toRegionName())), preserveKeys: true);

        $this->options($timezones->toArray());

        parent::setUp();
    }

    protected function formatTimezone(string $identifier): array
    {
        $tz = new CarbonTimeZone($identifier);

        return [$identifier => __(':timezone (:offset)', [
            'timezone' => $tz,
            'offset' => $tz->toOffsetName(),
        ])];
    }

    public function defaultByBrowser(): static
    {
        $this->default(null);
        $this->extraAttributes(fn (Component $component) => [
            'x-intersect' => 'if (!$wire.get("'.$component->getStatePath().'")) {
                $wire.set("'.$component->getStatePath().'", Intl.DateTimeFormat().resolvedOptions().timeZone);
            }',
        ], merge: true);

        return $this;
    }
}
