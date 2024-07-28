@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::pages.home.banner', ['header_name' => ''])

        {{-- listJob --}}
        @include('website::pages.job', ['header_name' => ''])
        


    </div>
@stop
@section('script')

@stop
