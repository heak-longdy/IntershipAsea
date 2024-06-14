{{-- <div class="pagination">
    <div class="pagination-left">
        <span>@lang('table.paginate.showing') {!! $paginate->firstItem() !!} - {!! $paginate->lastItem() !!}
            @lang('table.paginate.of')
            {!! number_format($paginate->total(), 0) !!}</span>
    </div>
    <div class="pagination-right">
        <div class="pagination-wrapper">
            <div class="pagination-item left {!! $paginate->currentPage() == 1 ? 'disabled' : '' !!}" s-click-link="{!! customUrl($paginate->previousPageUrl(), request()->all()) !!}">
                <i data-feather="chevron-left"></i>
            </div>

            @if ($paginate->lastPage() > 10)

                @if ($paginate->currentPage() >= 4)
                    <div class="pagination-item {!! $paginate->currentPage() == 1 ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != 1 ? url()->current() . '?page=1' : null, request()->all()) !!}">
                        <span>1</span>
                    </div>
                    <div class="pagination-item disabled">
                        <i data-feather="more-horizontal"></i>
                    </div>
                @else
                    @for ($i = 1; $i <= ($paginate->total() > 4 ? 4 : $paginate->lastPage()); $i++)
                        <div class="pagination-item {!! $paginate->currentPage() == $i ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != $i ? url()->current() . '?page=' . $i : null, request()->all()) !!}">
                            <span>{!! $i !!}</span>
                        </div>
                    @endfor
                @endif

                @if ($paginate->currentPage() >= 4 && $paginate->currentPage() < $paginate->lastPage() - 2)
                    @for ($i = $paginate->currentPage() - 1; $i <= $paginate->currentPage() + 1; $i++)
                        <div class="pagination-item {!! $paginate->currentPage() == $i ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != $i ? url()->current() . '?page=' . $i : null, request()->all()) !!}">
                            <span>{!! $i !!}</span>
                        </div>
                    @endfor
                @endif

                @if ($paginate->currentPage() < $paginate->lastPage() - 2)
                    <div class="pagination-item disabled">
                        <i data-feather="more-horizontal"></i>
                    </div>
                    <div class="pagination-item {!! $paginate->currentPage() == $paginate->lastPage() ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != $paginate->lastPage() ? url()->current() . '?page=' . $paginate->lastPage() : null, request()->all()) !!}">
                        <span>{!! $paginate->lastPage() !!}</span>
                    </div>
                @else
                    @for ($i = $paginate->lastPage() - 3; $i <= $paginate->lastPage(); $i++)
                        <div class="pagination-item {!! $paginate->currentPage() == $i ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != $i ? url()->current() . '?page=' . $i : null, request()->all()) !!}">
                            <span>{!! $i !!}</span>
                        </div>
                    @endfor
                @endif

            @else
                @for ($i = 1; $i <= $paginate->lastPage(); $i++)
                    <div class="pagination-item {!! $paginate->currentPage() == $i ? 'active' : '' !!}" s-click-link="{!! customUrl($paginate->currentPage() != $i ? url()->current() . '?page=' . $i : null, request()->all()) !!}">
                        <span>{!! $i !!}</span>
                    </div>
                @endfor
            @endif

            <div class="pagination-item right {!! $paginate->currentPage() == $paginate->lastPage() ? 'disabled' : '' !!}" s-click-link="{!! customUrl($paginate->nextPageUrl(), request()->all()) !!}">
                <i data-feather="chevron-right"></i>
            </div>
        </div>
    </div>
</div> --}}
<div class="pagination">
    <div class="rows-per-page">
        <span>Rows per page:</span>
        <select>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
        </select>
    </div>
    <div class="page-info">
        <span>1–5 of 9</span>
    </div>
    <div class="page-controls-tb">
        <button class="first-page btn" disabled><i class='bx bx-first-page'></i></button>
        <button class="prev-page btn" disabled><i class='bx bx-chevron-left'></i></button>
        <button class="next-page btn"><i class='bx bx-chevron-right'></i></button>
        <button class="last-page btn"><i class='bx bx-last-page'></i></button>
    </div>
</div>