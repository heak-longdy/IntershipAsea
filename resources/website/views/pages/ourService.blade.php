@extends('website::shared.layout')
@section('layout')
    <style>
        .ourServiceContainer {
            width: 100%;
        }

        .ourServiceContainer>h2 {
            text-align: center;
            font-size: 25px;
            color: #ff9900;
            margin: 60px 0 70px 0;
        }

        .ourSerItemLeft {
            display: flex;
            justify-content: flex-start;
            margin-top: -70px;
        }

        .ourSerItemLeft>.ItemLeft {
            /* width: 540px; */
            width: 100%;
            display: flex;
            /* align-items: flex-start; */
            align-items: center;
        }

        .ItemLeft>.ItemLeftText {
            /* flex: 1; */
            width: calc(60% - 250px);
            padding-right: 50px;
        }

        .h3 {
            font-size: 18px;
            color: #ff9900;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: initial;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .ourSerItemLayout {
            width: 100%;
            margin: 140px 0 70px 0;
        }

        .ourSerItemRight {
            display: flex;
            justify-content: flex-end;
            margin-top: -70px;
        }

        .ourSerItemRight>.ourSerItem {
            width: 540px;
            width: 100%;
            display: flex;
            flex-direction: row-reverse;
            /* align-items: flex-start; */
            align-items: center;
            grid-gap: 35px;
        }

        .ourSerText {
            /* flex: 1; */
            width: calc(60% - 250px);
            /* padding-left: 50px; */
        }

        .ItemLeftText>.ItemText>div>span,
        .ItemLeftText>div>.fontWeight,
        .ItemLeftText>.ItemText>div>.fontWeight,
        .ItemLeftText>div>span,
        .ourSerText>div>.fontWeight,
        .ourSerText>div>span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: initial;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .ourSerImage {
            width: 110px;
            height: 110px;
            min-width: 110px;
            min-height: 110px;
            position: relative;
            background: #ff9900;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 55px;
        }

        .ourSerImage>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .lineLeft {
            position: absolute;
            bottom: -45px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: flex-start;
            left: 61px;
            z-index: -1;

        }

        .chiLineTop {
            height: 1px;
            border-left: 0.125rem dashed hsla(223deg, 10%, 50%, 0.4);
            display: flex;
            justify-content: center;
            width: 1px;
            height: 100px;

        }

        .chiLineRight {
            height: 1px;
            width: 110px;
            border-top: 0.125rem dashed hsla(223deg, 10%, 50%, 0.4);

        }

        /* Our Version */
        .OurVision {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 30px;
            margin-bottom: 70px;
        }

        .OurVisionItem {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: flex-start;
            width: calc(100% / 3 - 30px);
        }

        .OurVisionText>h2 {
            font-size: 16px;
            color: #ff9900;
            margin-top: 0;
            margin-bottom: 10px;
            text-align: left;
        }

        .OurVisionText>p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .OurVisionText>p>strong {
            display: flex;
            align-items: center;
            padding-left: 18px;
            position: relative;
        }

        .OurVisionText>p>strong>i {
            font-size: 18px;
            position: absolute;
            left: 0;
            top: 0;
        }

        .ourSerItemRight>.ourSerItem>.ourSerImage>.lineLeft {
            flex-direction: column;
            align-items: flex-end;
            right: 60px;
            bottom: -45px;
        }

        .ItemText {}


        @media screen and (max-width: 1650px) {
            .ItemLeft>.ItemLeftText {
                width: calc(55% - 200px);
                padding-right: 15px;
            }

            .ourSerItem>.ourSerText {
                grid-gap: 20px;
                width: calc(65% - 250px);
            }
        }

        @media screen and (max-width: 1315px) {
            .OurVisionItem {
                width: calc(100% / 2 - 30px);
            }
        }

        @media screen and (max-width: 1210px) {
            .ourSerItem>.ourSerText {
                grid-gap: 20px;
                width: calc(67% - 250px);
            }
        }

        @media screen and (max-width: 700px) {
            .ourSerItemLayout {
                margin: 40px 0 70px 0;
            }

            .ourServiceContainer>h2 {
                margin: 60px 0 40px 0;
            }

            .ourSerItemLeft {
                display: flex;
                justify-content: flex-start;
                margin-top: 0;
            }

            .ourSerItemRight {
                display: flex;
                justify-content: flex-end;
                margin-top: 0;
            }

            .ourSerItemLeft>.ItemLeft {
                grid-gap: 35px;
                flex-direction: row-reverse;
            }

            .ourSerItemRight>.ourSerItem {}

            .ItemLeft>.ItemLeftText {
                width: 100%;
                padding-right: 0;
            }

            .ourSerItem>.ourSerText {
                width: 100%;
            }

            .lineLeft {
                display: none;
            }

            .ourSerItemLeft,
            .ourSerItemRight {
                margin-bottom: 25px;
            }

            .ItemLeftText>.ItemText>div>span,
            .ItemLeftText>div>.fontWeight,
            .ItemLeftText>.ItemText>div>.fontWeight,
            .ItemLeftText>div>span,
            .ourSerText>div>.fontWeight,
            .ourSerText>div>span {
                overflow: unset;
                display: block;
                -webkit-line-clamp: unset;
                -webkit-box-orient: vertical;
            }
        }

        @media screen and (max-width: 650px) {
            .OurVisionItem {
                width: 100%;
            }
        }

        @media screen and (max-width: 400px) {

            .ourSerItemLeft>.ItemLeft,
            .ourSerItemRight>.ourSerItem {
                flex-direction: column-reverse;
            }
        }
    </style>
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/aboutBg.png',
            'disableText' => 'Yes',
        ])
        {{-- our service --}}
        <div class="webContentListLayout">
            <div class="webContentList">
                <div class="ourServiceContainer">
                    <h2>Application Form</h2>
                    <div class="ourSerItemLayout">
                        <div class="ourSerItemLeft">
                            <div class="ItemLeft">
                                <div class="ItemLeftText">
                                    <h3 class="h3">1. Easy Application Process</h3>
                                    <div>
                                        <span class="fontWeight">Simple Online Booking:</span>
                                        <span>Our user-friendly website
                                            allows you to browse through a diverse range of
                                            internship opportunities and apply with ease. You
                                            can search by industry, company, and location to
                                            find the perfect match for your skills and interests.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Optional Consultation:</span>
                                        <span>To ensure the best fit, we
                                            oﬀer an optional consultation with a British national
                                            residing in Cambodia. This personalized session
                                            helps you understand the specifics of the internship,
                                            cultural expectations, and daily life in Cambodia.</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-rocket'></i>
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemRight">
                            <div class="ourSerItem">
                                <div class="ourSerText">
                                    <h3 class="h3">2. Pre-Departure Support</h3>
                                    <div>
                                        <span class="fontWeight">Flight and Visa Arrangements:</span>
                                        <span>We assist you with
                                            booking flights and handling visa applications, en-
                                            suring all necessary documents are in order.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Pre-Departure Orientation:</span>
                                        <span>We provide detailed
                                            pre-departure information, including cultural
                                            insights, packing tips, and travel advice to prepare
                                            you for your journey</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-badge-check'></i>
                                    {{-- <img
                                        src="https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2023/04/949086925-2023-apr-18-14-29-52-000000-Teaching.jpg" /> --}}
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemLeft">
                            <div class="ItemLeft">
                                <div class="ItemLeftText">
                                    <h3 class="h3">3. Post-Arrival Assistance</h3>
                                    <div class="ItemText">
                                        <div>
                                            <span class="fontWeight">Flight and Visa Arrangements:</span>
                                            <span>We assist you with
                                                booking flights and handling visa applications, en-
                                                suring all necessary documents are in order.</span>
                                        </div>
                                        <div>
                                            <span class="fontWeight">Pre-Departure Orientation:</span>
                                            <span>We provide detailed
                                                pre-departure information, including cultural
                                                insights, packing tips, and travel advice to prepare
                                                you for your journey</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-network-chart'></i>
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemRight">
                            <div class="ourSerItem">
                                <div class="ourSerText">
                                    <h3 class="h3">1. Easy Application Process</h3>
                                    <div>
                                        <span class="fontWeight">Flight and Visa Arrangements:</span>
                                        <span>We assist you with
                                            booking flights and handling visa applications, en-
                                            suring all necessary documents are in order.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Pre-Departure Orientation:</span>
                                        <span>We provide detailed
                                            pre-departure information, including cultural
                                            insights, packing tips, and travel advice to prepare
                                            you for your journey</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-camera-movie'></i>
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemLeft">
                            <div class="ItemLeft">
                                <div class="ItemLeftText">
                                    <h3 class="h3">1. Easy Application Process</h3>
                                    <div>
                                        <span class="fontWeight">Flight and Visa Arrangements:</span>
                                        <span>We assist you with
                                            booking flights and handling visa applications, en-
                                            suring all necessary documents are in order.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Pre-Departure Orientation:</span>
                                        <span>We provide detailed
                                            pre-departure information, including cultural
                                            insights, packing tips, and travel advice to prepare
                                            you for your journey</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-brush'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="underLine"></div>
                    <h2>For Providers </h2>
                    <div class="ourSerItemLayout">
                        <div class="ourSerItemLeft">
                            <div class="ItemLeft">
                                <div class="ItemLeftText">
                                    <h3 class="h3">1. Partnership Development</h3>
                                    <div>
                                        <span class="fontWeight">Consultation Services:</span>
                                        <span>We work closely with com-
                                            panies to identify and develop valuable internship
                                            opportunities. Our team provides expert advice on
                                            creating positions that are beneficial for both the
                                            intern and the organization.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Tailored Solutions:</span>
                                        <span>We understand that each com-
                                            pany has unique needs. Our tailored solutions
                                            ensure that the internships we help create align
                                            with your business goals and requirements.</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-rocket'></i>
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemRight">
                            <div class="ourSerItem">
                                <div class="ourSerText">
                                    <h3 class="h3">2. Recruitment and Selection</h3>
                                    <div>
                                        <span class="fontWeight">Vetting Process:</span>
                                        <span>We conduct a thorough vetting
                                            process to ensure that interns are well-suited for
                                            the positions oﬀered, matching their skills and
                                            interests with your organizational needs.</span>
                                    </div>
                                    <div>
                                        <span class="fontWeight">Intern Matching:</span>
                                        <span>Our sophisticated matching
                                            system ensures that you receive candidates who
                                            are not only qualified but also passionate about
                                            the opportunity.</span>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-badge-check'></i>
                                    <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ourSerItemLeft">
                            <div class="ItemLeft">
                                <div class="ItemLeftText">
                                    <h3 class="h3">3. Ongoing Support</h3>
                                    <div class="ItemText">
                                        <div>
                                            <span class="fontWeight">Intern Management:</span>
                                            <span>We provide support in man-
                                                aging interns, including setting goals, monitoring
                                                progress, and addressing any issues that may arise.</span>
                                        </div>
                                        <div>
                                            <span class="fontWeight">Feedback Mechanisms:</span>
                                            <span>Regular feedback sessions
                                                help ensure that the internship experience is posi-
                                                tive for both the intern and the organization.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ourSerImage">
                                    <i class='bx bx-network-chart'></i>
                                    {{-- <div class="lineLeft">
                                        <div class="chiLineTop"></div>
                                        <div class="chiLineRight"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="underLine"></div>
                    <h2 class="">
                        Our vision goes beyond simply placing interns.<br />
                        We want to foster a win-win development</h2>
                    <div class="OurVision">
                        <div class="OurVisionItem">
                            <div class="OurVisionText">
                                <h2 class="fontWeight">Economic and Human Capital Boost</h2>
                                <p><strong class="fontWeight"><i class='bx bx-check-circle'></i>&nbsp;Increasing
                                        Visitor Numbers:</strong>By attracting
                                    UK citizens to Cambodia, we contribute to the
                                    tourism sector and boost local businesses.</p>
                                <p><strong class="fontWeight">
                                        <i class='bx bx-check-circle'></i>&nbsp;Revenue Generation:</strong>Interns bring
                                    new
                                    revenue streams, benefiting local economies
                                    and fostering sustainable growth.</p>
                                <p><strong class="fontWeight">
                                        <i class='bx bx-check-circle'></i>&nbsp;Skill Enhancement:</strong>Our internships
                                    help
                                    develop the skills and capacities of both interns
                                    and local employees, enhancing the overall
                                    human capital of Cambodia.</p>
                            </div>
                        </div>
                        <div class="OurVisionItem">
                            <div class="OurVisionText">
                                <h2 class="fontWeight">Strengthening International Ties</h2>
                                <p><strong class="fontWeight"><i class='bx bx-check-circle'></i>&nbsp;Cultural
                                        Exchange:</strong>Our programs pro-
                                    mote cultural exchange, fostering greater un-
                                    derstanding and cooperation between the UK
                                    and Cambodia.</p>
                                <p><strong class="fontWeight">
                                        <i class='bx bx-check-circle'></i>&nbsp;Institutional Collaboration:</strong>We
                                    work
                                    closely with the British Embassy, British Cham-
                                    ber of Commerce, and local educational insti-
                                    tutions to enhance the professional land-
                                    scape in Cambodia.</p>
                            </div>
                        </div>
                        <div class="OurVisionItem">
                            <div class="OurVisionText">
                                <h2 class="fontWeight">Positive Community Impact</h2>
                                <p><strong class="fontWeight"><i class='bx bx-check-circle'></i>&nbsp;Local
                                        Engagement:</strong>Our interns often par-
                                    ticipate in community projects, contributing
                                    to the social and economic development of
                                    local communities.</p>
                                <p><strong class="fontWeight">
                                        <i class='bx bx-check-circle'></i>&nbsp;Sustainability Initiatives:</strong>We
                                    support
                                    sustainable practices and initiatives that bene-
                                    fit both the environment and the local popula-
                                    tion.</p>
                            </div>
                        </div>
                    </div>
                    <div class="underLine"></div>
                </div>
            </div>
        </div>
        {{-- WHO WE ARE --}}
        <div class="webContentListLayout paddingTop_Bot60">
            <div class="webContentList">
                <div class="hLeft">
                    <h3 class="colorYellow margin_bot20">Why ISEA?</h3>
                    <div>
                        <p>At ISEA, we are committed to providing a holistic and enriching intern-
                            ship experience that benefits all stakeholders. Our comprehensive ser-
                            vices ensure that interns are well-prepared, supported, and equipped
                            to make the most of their time in Cambodia, while companies receive
                            dedicated, skilled interns who add value to their operations. Together,
                            we create impactful opportunities that drive growth and development
                            for individuals, organizations, and the nation as a whole.</p>
                    </div>
                </div>
                <div class="hRight text_align_right">
                    <img src="{{ asset('website/img/ourService01.png') }}" alt="" class="border-radius wwaImg">
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
