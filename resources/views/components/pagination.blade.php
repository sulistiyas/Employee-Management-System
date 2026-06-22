@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="ems-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="ems-pagination__btn ems-pagination__btn--nav ems-pagination__btn--disabled" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="ems-pagination__btn ems-pagination__btn--nav" rel="prev" aria-label="{{ __('pagination.previous') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            {{-- Dots separator --}}
            @if (is_string($element))
                <span class="ems-pagination__dots">{{ $element }}</span>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="ems-pagination__btn ems-pagination__btn--active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="ems-pagination__btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="ems-pagination__btn ems-pagination__btn--nav" rel="next" aria-label="{{ __('pagination.next') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        @else
            <span class="ems-pagination__btn ems-pagination__btn--nav ems-pagination__btn--disabled" aria-disabled="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </span>
        @endif

    </nav>
@endif