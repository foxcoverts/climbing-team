@if ($paginator->hasMorePages())
    <div id="pagination" x-init x-intersect="$ajax('{{ $paginator->nextPageUrl() }}')"
        x-target="{{ $targets }} pagination"></div>
@else
    <div id="pagination"></div>
@endif
