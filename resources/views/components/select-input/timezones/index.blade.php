@use('Carbon\CarbonTimeZone')
@aware(['value'])

@php
    $groups = [
        CarbonTimeZone::AFRICA => __('Africa'),
        CarbonTimeZone::AMERICA => __('America'),
        CarbonTimeZone::ANTARCTICA => __('Antarctica'),
        CarbonTimeZone::ARCTIC => __('Arctic'),
        CarbonTimeZone::ASIA => __('Asia'),
        CarbonTimeZone::ATLANTIC => __('Atlantic'),
        CarbonTimeZone::AUSTRALIA => __('Australia'),
        CarbonTimeZone::EUROPE => __('Europe'),
        CarbonTimeZone::INDIAN => __('Indian'),
        CarbonTimeZone::PACIFIC => __('Pacific'),
    ];
    $utc = new CarbonTimeZone('UTC');
    $value = empty($value) ? $utc : new CarbonTimeZone($value);
@endphp

<x-select-input.timezones.option :timezone="$utc" />
@foreach ($groups as $group => $label)
    <optgroup label="{{ $label }}">
        @foreach (\Arr::map(CarbonTimeZone::listIdentifiers($group), function ($name) {
        return new CarbonTimeZone($name);
    }) as $timezone)
            <x-select-input.timezones.option :timezone="$timezone" />
        @endforeach
    </optgroup>
@endforeach
