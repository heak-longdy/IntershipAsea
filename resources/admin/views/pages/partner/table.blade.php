<div class="table">

    @if ($data->count() > 0)
        <div class="table-wrapper">
            <div class="paginationLayout2">
                @include('admin::components.pagination', ['paginate' => $data])
            </div>
            <div class="table-header">
                <div class="row table-row-5">
                    <span>Nº</span>
                </div>
                <div class="row table-row-10 text-left">
                    <span>Image</span>
                </div>
                <div class="row table-row-70 text-left">
                    <span>Title</span>
                </div>
                <div class="row table-row-10 text-left">
                    <span>Post Date</span>
                </div>
                <div class="row table-row-5">
                    <span></span>
                </div>
            </div>
            <div class="table-body">
                @foreach ($data as $index => $item)
                    <div class="column">
                        <div class="row table-row-5">
                            <span>{!! $data->currentPage() * $data->perPage() - $data->perPage() + ($index + 1) !!}</span>
                        </div>
                        <div class="row table-row-10">
                            <div class="thumbnail" data-fancybox data-src="{!! $item->image_url !!}">
                                <img src="{!! $item->image_url !!}"
                                    onerror="(this).src='{{ asset('images/logo/default.png') }}'" alt="">
                            </div>
                        </div>
                        <div class="row table-row-70 text left">
                            <span>{!! isset($item->title) ? $item->title : '---' !!}</span>
                        </div>
                        <div class="row table-row-10 text left">
                            <span>{!! isset($item->PostDateFor) ? $item->PostDateFor : '---' !!}</span>

                        </div>
                        <div class="row table-row-5">
                            <div class="dropdown">
                                <i data-feather="more-vertical" class="action-btn" id="dropdownMenuButton"
                                    data-mdb-toggle="dropdown" aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if ($status != 'trash')
                                        @can('user-update')
                                            <li>
                                                <a class="dropdown-item" s-click-link="{!! route('admin-' . $routeName . '-edit', $item->id) !!}">
                                                    <i data-feather="edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                            </li>
                                            @if ($item->status == 2)
                                                <li>
                                                    <a class="dropdown-item enable-btn"
                                                        onclick="$onConfirmMessage(
                                                            '{!! route('admin-' . $routeName . '-edit', ['id' => $item->id, 'status' => 1]) !!}',
                                                            '@lang('dialog.msg.enable', ['name' => isset($item->name) ? $item->name : $item->name])',
                                                            {
                                                                confirm: '@lang('dialog.button.enable')',
                                                                cancel: '@lang('dialog.button.cancel')'
                                                            },
                                                        );">
                                                        <i data-feather="rotate-ccw"></i>
                                                        <span>@lang('table.option.enable')</span>
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item disable-btn"
                                                        onclick="$onConfirmMessage(
                                                            '{!! route('admin-' . $routeName . '-edit', ['id' => $item->id, 'status' => 2]) !!}',
                                                            '@lang('dialog.msg.disable', ['name' => isset($item->name) ? $item->name : $item->name])',
                                                            {
                                                                confirm: '@lang('dialog.button.disable')',
                                                                cancel: '@lang('dialog.button.cancel')'
                                                            }
                                                        );">
                                                        <i data-feather="x-circle"></i>
                                                        <span>@lang('table.option.disable')</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endcan
                                        @can('user-delete')
                                            <li>
                                                <a class="dropdown-item text-danger trash-btn"
                                                    data-url="{!! route('admin-' . $routeName . '-edit', $item->id) !!}" data-name="{!! isset($item->name) ? $item->name : $item->name !!}">
                                                    <i data-feather="trash-2"></i>
                                                    <span>@lang('table.option.delete')</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" @click="deleteDialog({{ $item }})">
                                                    <i data-feather="trash-2"></i>
                                                    <span>@lang('table.option.delete')d</span>
                                                </a>
                                            </li>
                                            
                                        @endcan
                                    @else
                                        @can('user-delete')
                                            <li>
                                                <a class="dropdown-item disable-btn"
                                                    onclick="$onConfirmMessage(
                                                        '{!! route('admin-' . $routeName . '-edit', ['id' => $item->id, 'status' => 'restore']) !!}',
                                                        '@lang('dialog.msg.restore', ['name' => $item->name ? $item->name : $item->name])',
                                                        {
                                                            confirm: '@lang('dialog.button.restore')',
                                                            cancel: '@lang('dialog.button.cancel')'
                                                        }
                                                    );">
                                                    <i data-feather="rotate-ccw"></i>
                                                    <span>@lang('table.option.restore')</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-btn"
                                                    onclick="$onConfirmMessage(
                                                        '{!! route('admin-' . $routeName . '-edit', $item->id) !!}' ,
                                                        '@lang('dialog.msg.delete', ['name' => $item->name ? $item->name : $item->name])',
                                                        {
                                                            confirm: '@lang('dialog.button.delete')',
                                                            cancel: '@lang('dialog.button.cancel')'
                                                        }
                                                    );">
                                                    <i data-feather="trash"></i>
                                                    <span>@lang('table.option.delete')</span>
                                                </a>
                                            </li>
                                        @endcan
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="paginationLayout">
                @include('admin::components.paginationNumber', ['paginate' => $data])
            </div>
        </div>
    @else
        @component('admin::components.empty', [
            'name' => __('No data'),
            'msg' => 'You can create a new {{ $routeName }} by clicking the button below',
            'permission' => 'Position-create',
            'url' => route('admin-' . $routeName . '-create'),
            'button' => '{{ $createName }}',
        ])
        @endcomponent
    @endif
</div>

