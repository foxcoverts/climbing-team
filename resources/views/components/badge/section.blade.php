@use('App\Enums\Section')
@props(['section'])

@switch($section)
    @case(Section::Squirrel)
    @case(Section::Beaver)

    @case(Section::Cub)
    @case(Section::Scout)

    @case(Section::Explorer)
        @php($color = 'pink')
    @break

    @case(Section::Network)
    @case(Section::Adult)
        @php($color = 'lime')
    @break

    @case(Section::Parent)
        @php($color = 'gray')
    @break

    @default
        @php($color = 'gray')
@endswitch

<x-badge :color="$color" {{ $attributes }}>@lang("app.user.section.{$section->value}")</x-badge>
