@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::pages.home.banner', [
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
        <div class="homeLayout paddingTop_Bot60 bgYellow"
            style="color: #fff;padding: 35px 0;background: url('../../website/img/home06.png');background-size: cover;    height: 350px;">
            <div class="homeList" style="align-items: center;">
                <div class="hLeft">
                    <h3 class="margin_bot20">Book A Free Consultation With Our International Team</h3>
                    <a href="" class="homeContactus" style="text-decoration: none;">
                        <div class=" bgYellow"
                            style="    width: fit-content;
    height: 40px;
    padding: 0 15px;
    display: flex;
    align-items: center;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    align-items: center;
    display: flex;
    grid-gap: 5px;">
                            <i class='bx bx-phone' style="font-size: 25px;"></i>
                            <spna>Contact Us</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script></script>
@stop
