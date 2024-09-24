@extends('website::shared.layout')
@section('layout')
    <style>
        .h3Title {
            text-align: center;
            margin: 60px 0;
            font-size: 25px;
            color: #ff9900;
        }

        .aboutContainer {
            width: fit-content;
            margin: 60px 180px 0;
        }

        .aboutContainer>h2 {
            text-align: center;
            font-size: 25px;
            color: #ff9900;

        }


        .aboutItem {
            display: flex;
            grid-gap: 60px;
            align-items: flex-start;
            margin-bottom: 60px;

        }

        /* .aboutItem:last-child {
                            margin-bottom: 0;
                        } */

        .aboutItem>img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
        }

        .aboutItem>.aboutText {
            flex: 1;
        }

        .aboutItem>.aboutText>h3 {
            font-size: 25px;
            color: #ff9900;
            margin-bottom: 15px;
        }

        /* OurFunders */
        .OurFunders {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 50px;
            margin-bottom: 60px;
        }

        .OurFunderIitem {
            text-align: center;
            width: calc(100% / 2 - 50px);
        }

        .OurFunderIitem>img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff9900;
            padding: 5px;
        }

        .OurFunderIitem>h3 {
            margin: 10px 0;
            font-size: 20px;
        }

        .OurFunderIitem>p {
            text-align: left;
        }

        /* OurValues */
        .OurValues {
            display: flex;
            grid-gap: 50px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 100px;
        }

        .OurValueIitem {
            text-align: center;
            width: calc(100% / 3 - 50px);
        }

        .OurValueHeader {
            display: flex;
            align-items: center;
            grid-gap: 20px;
            margin-bottom: 20px;
        }

        .OurValueHeader>h3 {
            font-size: 20px;
        }

        .OurValueHeader>img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff9900;
            padding: 5px;
        }

        .OurValueIitem>p {
            text-align: left;
        }

        @media screen and (max-width: 1200px) {
            .aboutContainer {
                margin: 60px 0;
            }
        }

        @media screen and (max-width: 700px) {
            .OurValueIitem {
                text-align: center;
                width: calc(100% / 2 - 50px);
            }
        }

        @media screen and (max-width: 550px) {

            .OurFunderIitem,
            .OurValueIitem {
                width: calc(100% / 1);
            }
        }
    </style>
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/aboutBg.png',
            'disableText' => 'Yes',
        ])
        <div class="webContentListLayout">
            <div class="webContentList" style="flex-direction: column;">
                {{-- about introduction --}}
                <div class="aboutContainer">
                    <div class="aboutItem">
                        <img src="{{ asset('website/img/ourService01.png') }}" alt="" class="border-radius wwaImg">
                        <div class="aboutText">
                            <h3>Our Mission</h3>
                            <p>At Internship SEA (ISEA), our mission is to bridge the gap between the in-
                                creasing demand for international internships among UK citizens and the
                                growing need for skilled professionals in South East Asia. We aim to pro-
                                vide exceptional internship experiences that are mutually benecial for
                                interns and host organizations, contributing to the growth and develop-
                                ment of Cambodia.</p>
                        </div>
                    </div>
                    <div class="aboutItem">
                        <img src="{{ asset('website/img/ourService01.png') }}" alt="" class="border-radius wwaImg">
                        <div class="aboutText">
                            <h3>Our Vision</h3>
                            <p>We envision a world where young professionals from the UK can gain
                                invaluable international experience while contributing positively to the
                                host country's development. By creating a platform that supports both
                                interns and providers, we strive to be the leading facilitator of impactful
                                internships in South East Asia, setting a benchmark for quality and ser-
                                vice in the international internship sector.</p>
                        </div>
                    </div>
                </div>
                <div class="underLine"></div>
                {{-- Our Founders --}}
                <h3 class="h3Title">Our Founders</h3>
                <div class="OurFunders">

                    <div class="OurFunderIitem">
                        <img src="{{ asset('website/img/profile02.png') }}" />
                        <h3 class="fontWeight">Tom Starkey</h3>
                        <p>With over 15 years of international experience, Tom Starkey has a
                            proven track record in managing development and private sector proj-
                            ects with interns and volunteers. He has co-founded the HR platform
                            Next Step and managed eco-tourism projects. As the Managing Direc-
                            tor of ISEA, Tom leads partnerships with the British Embassy and Brit-
                            ish Chamber of Commerce, oversees marketing strategies, and facili-
                            tates the entire internship process. His deep connection with Cambo-
                            dia is reected in his Cambodia Lifestyle platform, which promotes
                            the best of Cambodian experiences.</p>
                    </div>
                    <div class="OurFunderIitem">
                        <img src="{{ asset('website/img/profile01.png') }}" />
                        <h3 class="fontWeight">Veronica Pou</h3>
                        <p>As an HR specialist for a leading Cambodian rm, Veronica Pou
                            brings a deep understanding of the Cambodian market and ex-
                            tensive connections within the recruitment landscape. She
                            serves as the Chief Financial Operator, responsible for initial cap-
                            ital investment and building the nancial strategy for ISEA in its
                            rst three years.</p>
                    </div>
                </div>
                <div class="underLine"></div>
                {{-- Our Values --}}
                <h3 class="h3Title">Our Values</h3>
                <div class="OurValues">

                    <div class="OurValueIitem">
                        <div class="OurValueHeader">
                            <img src="{{ asset('website/img/ourService01.png') }}" />
                            <h3 class="fontWeight">Excellence</h3>
                        </div>
                        <p>We strive to provide the highest
                            quality of service to our interns and
                            partner organizations, ensuring that
                            every internship experience is en-
                            riching and valuable.</p>
                    </div>
                    <div class="OurValueIitem">
                        <div class="OurValueHeader">
                            <img src="{{ asset('website/img/ourService01.png') }}" />
                            <h3 class="fontWeight">Excellence</h3>
                        </div>
                        <p>We strive to provide the highest
                            quality of service to our interns and
                            partner organizations, ensuring that
                            every internship experience is en-
                            riching and valuable.</p>
                    </div>
                    <div class="OurValueIitem">
                        <div class="OurValueHeader">
                            <img src="{{ asset('website/img/ourService01.png') }}" />
                            <h3 class="fontWeight">Excellence</h3>
                        </div>
                        <p>We strive to provide the highest
                            quality of service to our interns and
                            partner organizations, ensuring that
                            every internship experience is en-
                            riching and valuable.</p>
                    </div>
                    <div class="OurValueIitem">
                        <div class="OurValueHeader">
                            <img src="{{ asset('website/img/ourService01.png') }}" />
                            <h3 class="fontWeight">Excellence</h3>
                        </div>
                        <p>We strive to provide the highest
                            quality of service to our interns and
                            partner organizations, ensuring that
                            every internship experience is en-
                            riching and valuable.</p>
                    </div>
                    <div class="OurValueIitem">
                        <div class="OurValueHeader">
                            <img src="{{ asset('website/img/ourService01.png') }}" />
                            <h3 class="fontWeight">Excellence</h3>
                        </div>
                        <p>We strive to provide the highest
                            quality of service to our interns and
                            partner organizations, ensuring that
                            every internship experience is en-
                            riching and valuable.</p>
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


    </div>
@stop
@section('script')
    <script></script>
@stop
