@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/aboutBg.png',
            'text1' => 'Seach or view the latest internships below',
            'text2' => 'Need more info?',
            'btnText' => "Let's talk!",
        ])
        <div class="jobLayout">
            <div class="jobListing">
                <h3 class="jobTitle">Intership Programmer</h3>
                <div class="jobContainer">
                    @foreach ($data as $index => $item)
                        @include('website::components.jobItem', ['item' => $item])
                    @endforeach
                </div>
                <div class="paginationLayout">
                    @include('website::components.paginationNumber', ['paginate' => $data])
                </div>
            </div>
        </div>
         {{-- Contact Us --}}
         @include('website::components.contact', [
            'header_name' => '',
            'imgUrl' => '../../website/img/home06.png',
            'text1' => 'Find once in lifetime Internships Programmes in Southeast Asia',
            'text2' => 'Are you looking for interns?',
            'btnText' => 'click here',
        ])
    </div>
@stop
@section('script')
    <script></script>
@stop
