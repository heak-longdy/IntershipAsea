@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper">
        <div class="header">
            @include('admin::shared.header', ['header_name' => __('Shop Report')])
            <div class="header-tab">
                <div class="header-tab-wrapper">
                    <div class="menu-row">
                        <div class="menu-item {!! Request::is('admin/report/shop/1') ? 'active' : '' !!}" s-click-link="{!! route('admin-report-shop', 1) !!}">
                           All</div>
                    </div>
                </div>
                <div class="header-action-button">
                    <form class="filter" action="{!! url()->current() !!}" method="GET">
                        <div class="form-row">
                            <select name="id">
                                <option value=""> All Shop</option>
                                @foreach ($shopData as $shop)
                                    <option {{ $shop->id == request('id') ? 'selected' : '' }} value="{{ $shop->id }}">  {{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <input type="text" name="from_date" placeholder="<?php echo date('Y-m-d');?>"    value="{!! request('from_date') !!}" id="from_date" autocomplete="off">
                        </div>
                        <div class="form-row">
                            <input type="text" name="to_date" placeholder="<?php echo date('Y-m-d');?>"    value="{!! request('to_date') !!}" id="to_date" autocomplete="off">
                        </div>
                        <button mat-flat-button type="submit" class="btn-create bg-success">
                            <i data-feather="search"></i>
                            <span>Search</span>
                        </button>
                    </form>
                   
                    <button s-click-link="{!! url()->current() !!}">
                        <i data-feather="refresh-ccw"></i>
                        <span>@lang('user.button.reload')</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="content-body">
            @include('admin::pages.report.shop.table')
        </div>
    </div>
@stop
@section('script')
    <script lang="ts">
        $(document).ready(function() {
            $("#from_date,#to_date").datepicker({
                changeYear: true,
                gotoCurrent: true,
                yearRange: "-1:+1",
                dateFormat: "yy-mm-dd",
            });
            $("#from_date").change(function() {
                let str = $(this).val();
                $("#to_date").datepicker("option", "minDate", new Date(str));
            });
            const date = $('#from_date').val();
            if(date){
                $("#to_date").datepicker("option", "minDate", new Date(date));
            }
        });
    </script>
@stop