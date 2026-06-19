@props(['paginator'])

@if ($paginator->hasPages())
    <div class="ems-pagination">
        <p class="ems-pagination__info">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="ems-pagination__links">
            @if ($paginator->onFirstPage())
                <span class="ems-pagination__btn ems-pagination__btn--disabled">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="ems-pagination__btn">&laquo;</a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="ems-pagination__btn ems-pagination__btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="ems-pagination__btn">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="ems-pagination__btn">&raquo;</a>
            @else
                <span class="ems-pagination__btn ems-pagination__btn--disabled">&raquo;</span>
            @endif
        </div>
    </div>
@endif