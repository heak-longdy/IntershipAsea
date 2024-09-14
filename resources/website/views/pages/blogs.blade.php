@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::pages.home.banner', ['header_name' => ''])

        {{-- listJob --}}
        <div class="webContentListLayout">
            <div class="webContentList">

                <div class="blog-container">
                    <h2>Latest Blog Articles</h2>
                    <div class="blog-grid">
                        @foreach ($data as $index => $item)
                            @include('website::components.blogItem', ['item' => $item,'url'=>'/job/detail/{{$item?->id}}'])
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('script')
    <script></script>
@stop
