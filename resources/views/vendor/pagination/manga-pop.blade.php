@if ($paginator->hasPages())
    <nav class="manga-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="manga-pagination__meta">
            <span class="manga-pagination__sticker">PAGE FLOW</span>
            <span class="manga-pagination__summary">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
        </div>

        <div class="manga-pagination__controls">
            @if ($paginator->onFirstPage())
                <span class="manga-page-link is-disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a class="manga-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="manga-page-gap">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="manga-page-link is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="manga-page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="manga-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="manga-page-link is-disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
