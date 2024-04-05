@props(['loading' => null])
@if ($paginator->hasMorePages())
    <div id="pagination" x-init x-target="{{ $targets }} pagination"
        x-intersect="$ajax('{{ $paginator->nextPageUrl() }}')">
        @isset($loading)
            @include($loading)
        @endisset
    </div>
@else
    <div id="pagination"></div>
@endif
