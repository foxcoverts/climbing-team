@use('App\Enums\Section')
@props([
    'section',
    'color' => match ($section) {
        Section::Squirrel, Section::Beaver, Section::Cub, Section::Scout, Section::Explorer => 'pink',
        Section::Network, Section::Adult => 'lime',
        Section::Parent => 'gray',
        default => 'gray',
    },
    'icon' => null,
    'label' => __('app.user.section.' . $section->value),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
