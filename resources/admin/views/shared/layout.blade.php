@extends('admin::index')
@section('index')

    <div class="container">
        <div class="container-wrapper">
            <div class="sidebar" id="sidebar" class="sidebar">
                @include('admin::shared.sidebar')
            </div>
            
            <div class="content" id="content" x-data={} >
                {{-- @include('admin::shared.header', ['header_name' => '']) --}}
                @yield('layout')
                @include('admin::components.confirm-dialog')
                @include('admin::components.select-option')
                @include('admin::components.logout')
                <div id="jsScroll" class="scroll">
                    <i class='bx bx-up-arrow-alt' ></i>
                </div>      
            </div>
        </div>
    </div>
@stop
