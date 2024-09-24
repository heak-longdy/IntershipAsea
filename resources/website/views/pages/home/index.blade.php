@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/ourService01.png',
            'text1' => 'Find once in lifetime Internships Programmes in Southeast Asia',
            'text2' => 'Are you looking for interns?',
            'btnText' => 'click here',
        ])

        {{-- WHO WE ARE --}}
        <div class="webContentListLayout paddingTop_Bot60">
            <div class="webContentList">
                <div class="hLeft">
                    <h3 class="colorYellow margin_bot20">WHO WE ARE</h3>
                    <div>
                        <p>Founded by UK and Cambodian nationals, we specialise in premium, tailored
                            internships for UK citizens in South East Asia. We are passionate about
                            quality internships in SEA, and understand the needs of our interns and
                            internship providers.</p>
                        <a href="#" class="colorYellow">Learn why we do it here</a>
                    </div>
                </div>
                <div class="hRight text_align_right">
                    <img src="{{ asset('website/img/ourService01.png') }}" alt="" class="border-radius wwaImg">
                </div>
            </div>
        </div>

        {{-- Why us? --}}
        <div class="webContentListLayout paddingTop_Bot60 bgYellow" style="color: #484646;padding: 35px 0;">
            <div class="webContentList">
                <div class="hLeft">
                    <h3 class="margin_bot20">Why us?</h3>
                    <div>
                        <p style="display: flex;margin-bottom: 7px;">
                            <i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">Full pre & post-landing services, in-country orientation &
                                support</span>
                        </p>
                        <p style="display: flex;margin-bottom: 7px;">
                            <i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">High-return professional development at reputable
                                companies</span>
                        </p>
                        <p style="display: flex;margin-bottom: 7px;">
                            <i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">Fun and fullling courses that benet host country
                                development</span>
                        </p>
                    </div>
                </div>
                <div class="colLine"></div>
                <div class="hRight ">
                    <h3 class="margin_bot20">Why South East Asia?</h3>
                    <div>
                        <p style="display: flex;margin-bottom: 7px;">
                            <i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">Gain valuable experience in rapidly growing Southeast
                                Asian economies</span>
                        </p>
                        <p style="display: flex;margin-bottom: 7px;"><i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">Work within international teams, gain new perspectives &
                                expand your network</span>
                        </p>
                        <p style="display: flex;margin-bottom: 7px;"><i class='bx bx-check-circle'></i>&nbsp;
                            <span style="margin-top: -2px;">Enjoy travel and cultural experiences as part of your
                                course</span>
                        </p>
                    </div>
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

        {{-- listJob --}}
        <div class="jobLayout" style="background: url('../../website/img/homeProgram.png');background-size: cover;">
            <div class="jobListing">
                <h3 class="jobTitle">Intership Programmer</h3>
                <div class="jobContainer">
                    @foreach ($jobs as $index => $item)
                        @include('website::components.jobItem', ['item' => $item])
                    @endforeach
                </div>
            </div>
            <div class="viewMore">
                <a href="">
                    <div>See More<i class='bx bx-chevron-right'></i></div>
                </a>
            </div>
        </div>


        {{-- View our testimonials --}}
        <div class="webContentListLayout" style="padding: 35px 0;">
            <div class="webContentList testimonialsList" style="align-items: center;flex-direction: column;">
                <h3 class="colorYellow">View our testimonials</h3>
                <section>
                    <div class="blog">
                        <div class="container">
                            <div class="owl-carousel owl-theme blog-post">
                                <div class="blog-content" data-aos="fade-right" data-aos-delay="200">
                                    <img src="{{ asset('website/img/profile01.png') }}" alt="post-1">
                                    <div class="blog-title">
                                        <p>Nica Pou, Director Sales & Marketing</p>
                                        <span>“Quote here from the company that says that ISEA is a qualied compa-
                                            ny to carry out the so and so this is made up carry on example”</span>
                                    </div>
                                </div>
                                <div class="blog-content" data-aos="fade-right" data-aos-delay="200">
                                    <img src="{{ asset('website/img/profile02.png') }}" alt="post-1">
                                    <div class="blog-title">
                                        <p>Nica Pou, Director Sales & Marketing</p>
                                        <span>“Quote here from the company that says that ISEA is a qualied compa-
                                            ny to carry out the so and so this is made up carry on example”</span>
                                    </div>
                                </div>
                                <div class="blog-content" data-aos="fade-right" data-aos-delay="200">
                                    <img src="{{ asset('website/img/ourService01.png') }}" alt="post-1">
                                    <div class="blog-title">
                                        <p>Nica Pou, Director Sales & Marketing</p>
                                        <span>“Quote here from the company that says that ISEA is a qualied compa-
                                            ny to carry out the so and so this is made up carry on example”</span>
                                    </div>
                                </div>
                            </div>
                            <div class="owl-navigation">
                                <span class="owl-nav-prev"><i class='bx bx-chevron-left'></i></span>
                                <span class="owl-nav-next"><i class='bx bx-chevron-right'></i></span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        {{-- Our Partners --}}
        <div class="webContentListLayout" style="padding-bottom: 60px;">
            <div class="webContentList" style="align-items: center;flex-direction: column;">
                <h3 class="colorYellow" style="padding-bottom: 35px;">Our Partners</h3>
                <div class="partnerLayout">
                    <div class="partnerGp">
                        <div class="partnerItem">
                            <div class="partnerImg">
                                {{-- <img src="{{ asset('website/img/partner01.png') }}" alt="post-1"> --}}
                                <i class='bx bxl-whatsapp'></i>
                            </div>
                            <div class="partnerText">
                                <p>1. Easy Application Process</p>
                            </div>
                        </div>
                    </div>
                    <div class="partnerGp">
                        <div class="partnerItem">
                            <div class="partnerImg">
                                {{-- <img src="{{ asset('website/img/partner01.png') }}" alt="post-1"> --}}
                                <i class='bx bxl-facebook-circle'></i>
                            </div>
                            <div class="partnerText">
                                <p>1. Easy Application Process</p>
                            </div>
                        </div>
                    </div>
                    <div class="partnerGp">
                        <div class="partnerItem">
                            <div class="partnerImg">
                                {{-- <img src="{{ asset('website/img/partner01.png') }}" alt="post-1"> --}}
                                <i class='bx bxl-youtube'></i>
                            </div>
                            <div class="partnerText">
                                <p>1. Easy Application Process</p>
                            </div>
                        </div>
                    </div>
                    <div class="partnerGp">
                        <div class="partnerItem">
                            <div class="partnerImg">
                                {{-- <img src="{{ asset('website/img/partner01.png') }}" alt="post-1"> --}}
                                <i class='bx bxl-linkedin'></i>
                            </div>
                            <div class="partnerText">
                                <p>1. Easy Application Process</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
@section('script')
    <script>
        // owl-crousel for blog
        const responsive = {
            0: {
                items: 1
            },
            320: {
                items: 1
            },
            560: {
                items: 1
            },
            960: {
                items: 2
            }
        }
        $('.owl-carousel').owlCarousel({
            loop: true,
            autoplay: false,
            autoplayTimeout: 3000,
            dots: false,
            nav: true,
            navText: [$('.owl-navigation .owl-nav-prev'), $('.owl-navigation .owl-nav-next')],
            responsive: responsive
        });
    </script>
@stop
