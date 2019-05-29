@if ($paginator->hasPages())
    <nav class="pagination pagination_news">
        <ul class="pagination_ul">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination_li __previous __off"><a href="#" class="pagination_lk">назад</a></li>
            @else
                <li class="pagination_li __previous"><a href="{{ $paginator->previousPageUrl() }}" class="pagination_lk">назад</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination_li __active"><a href="#" class="pagination_lk">{{ $page }}</a></li>
                        @else
                            <li class="pagination_li"><a href="{{ $url }}" class="pagination_lk">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination_li __next"><a href="{{ $paginator->nextPageUrl() }}" class="pagination_lk">далее</a></li>
            @else
                <li class="pagination_li __next __off"><a href="#" class="pagination_lk">далее</a></li>
            @endif
        </ul>
    </nav>
@endif
