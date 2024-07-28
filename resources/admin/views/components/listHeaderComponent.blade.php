<div class="header box-shadow-bottom">
    <div class="header-tab">
        <div class="header-tab-wrapper">
            <div class="menu-row">
                <div class="tabs">
                    <a href="{!! route('admin-' . $routeName . '-list', 1) !!}" class="{!! Request::is('admin/' . $routeName . '/list/1') ? 'tabActive' : '' !!}">
                        <i class='bx bx-data'></i>
                        Active
                    </a>
                    <a href="{!! route('admin-' . $routeName . '-list', 2) !!}" class="{!! Request::is('admin/' . $routeName . '/list/2') ? 'tabActive' : '' !!}">
                        <i class='bx bx-navigation'></i>
                        Disable
                    </a>
                    <a href="{!! route('admin-' . $routeName . '-list', 'trash') !!}" class="{!! Request::is('admin/' . $routeName . '/list/trash') ? 'tabActive' : '' !!}">
                        <i class='bx bx-trash-alt'></i>
                        Trash
                    </a>
                </div>
            </div>
        </div>
        <div class="header-action-button">
            <form class="filter" action="{!! url()->current() !!}" method="GET">
                @if ($filterStatus)
                    <div class="form-row w80">
                        <select name="payment_status">
                            <option value="">All Status</option>
                            <option value="Pending" {!! request('payment_status') == 'Pending' ? 'selected' : '' !!}> Pending</option>
                            <option value="Paid" {!! request('payment_status') == 'Pending' ? 'selected' : '' !!}> Paid</option>
                        </select>
                    </div>
                @endif
                <button mat-flat-button type="submit" class="bg-success btnSearch">
                    <i class='bx bx-search'></i>
                </button>
            </form>
            <button s-click-link="{!! url()->current() !!}">
                <i class='bx bx-revision'></i>
                <span>Reload</span>
            </button>
            <button class="btn btn-create" s-click-link="{!! route('admin-' . $routeName . '-create') !!}">
                <i class='bx bx-plus'></i>
                <span>{{ $createName }}</span>
            </button>
        </div>
    </div>
</div>
