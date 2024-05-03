@props(['loading' => null, 'targets' => ''])
@if ($paginator->hasMorePages())
    <div id="pagination" x-init
        x-intersect="$ajax({{ Js::from($paginator->nextPageUrl()) }}, { target: '{{ $targets }} pagination' })">
        @isset($loading)
            @include($loading)
        @endisset
    </div>
@else
    <div id="pagination"></div>
@endif
