@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/ourService01.png',
            'clickhere' => 'disable',
        ])

        <style>
            .itemDetailBody {
                margin: 25px 0;
            }

            .btnJob {
                /* padding: 10px 20px; */
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                padding: 8px 15px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                grid-gap: 7px;
                background: none;
            }

            .margin25 {
                margin: 25px 0;
            }

            .btnJob>i {
                font-size: 25px;
            }

            .view-job {
                /* background-color: #ff9900; */
                /* color: #fff; */

            }

            .apply {
                background-color: #ff9900;
                color: #fff;
            }

            .btnJob:hover {
                opacity: 0.9;
            }

            .itemDetailLeft>a,
            .itemDetailTitle>a {
                text-decoration: unset;
            }
            .jobAmount{
                margin: 10px 0;
                font-size: 20px;
            }
        </style>

        {{-- WHO WE ARE --}}
        {{-- <div class="homeLayout paddingTop_Bot60">
            <div class="homeList">
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
        </div> --}}
        <div class="webContentListLayout marginTop_Bot30">
            <div class="webContentList">
                <div class="itemDetailLayout">
                    <div class="itemDetailGp">
                        <div class="itemDetailLeft">
                            <div class="itemDetailTitle">
                                <h3>Internship details</h3>
                                <a href="{{ route('web-apply-form') }}">
                                    <button type="button" class="btnJob apply"><i
                                            class='bx bx-right-top-arrow-circle bx-fade-right-hover'></i><span>Apply
                                            Now</span></button>
                                </a>
                            </div>
                            {{-- <a href="{{ route('web-apply-form') }}">
                                <button type="button" class="btnJob apply"><i
                                        class='bx bx-right-top-arrow-circle bx-fade-right-hover'></i><span>Apply
                                        Now</span></button>
                            </a> --}}

                            <div class="itemDetailBody">
                                
                                <div class="fontWeight">Placement Name:&nbsp;Marketing Intern at Cambodia Life Style</div>
                                <div class="jobAmount fontWeight colorYellow">
                                    $900.00
                                </div>
                                <div><span class="fontWeight">Role:</span>&nbsp;Marketing Intern</div>
                                <div><span class="fontWeight">Type of Placement:</span>&nbsp;Full-Time Internship</div>
                                <div><span class="fontWeight">Duration:</span>&nbsp;6 Months</div>
                                <div>
                                    <p class="fontWeight">Dates Available:</p>
                                    <div class="paddingItemDetail">
                                        <p>Start Date: August 1, 2024</p>
                                        <p>End Date: January 31, 2025</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="fontWeight">Company Description:</p>
                                    <div class="paddingItemDetail">
                                        <p>
                                            Join Cambodia Lifestyle, a leading lifestyle and media platform, as a Marketing
                                            Intern and gain hands-on
                                            experience in digital marketing, content creation, and brand promotion. This
                                            internship oﬀers a unique op-
                                            portunity to work with a dynamic team, develop your marketing skills, and
                                            immerse yourself in the vibrant
                                            culture of Cambodia.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p class="fontWeight">Intern Benefits:</p>
                                    <div class="paddingItemDetail">
                                        <p>
                                            Professional Growth & ROI: Gain practical experience in marketing and build a
                                            diverse skill set
                                            that will enhance your career prospects at an international company
                                            Cultural Immersion: Live and work in Phnom Penh, Cambodia, experiencing its rich
                                            history,
                                            culture, and lifestyle.
                                            Networking: Connect with professionals, influencers, and businesses in the
                                            tourism and life
                                            style sectors.
                                            Support: Receive comprehensive pre-departure and post-arrival support, including
                                            visa assis
                                            tance, accommodation arrangements, and in-country orientation.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p class="fontWeight">Qualifications:</p>
                                    <div class="paddingItemDetail">
                                        <p>
                                            Education: Pursuing or recently completed a degree in Marketing, Communications,
                                            Business,
                                            or a related field.
                                            Skills: Strong written and verbal communication skills, proficiency in social
                                            media platforms,
                                            basic knowledge of SEO and digital marketing tools, creativity, and attention to
                                            detail.
                                            Experience: Previous experience in marketing or related fields is a plus but not
                                            required.
                                            Attributes: Self-motivated, proactive, adaptable, and a team player with a
                                            passion for market
                                            ing and cultural exchange.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="underLine"></div> --}}
                            <a href="{{ route('web-apply-form') }}">
                                <button type="button" class="btnJob apply margin25"><i
                                        class='bx bx-right-top-arrow-circle bx-fade-right-hover'></i><span>Apply
                                        Now</span></button>
                            </a>
                        </div>
                        <div class="itemDetailRight">
                            <h3>Similar Posts</h3>
                            @foreach ($blogRelates as $index => $item)
                                @include('website::components.jobItemGrid', [
                                    'item' => $item,
                                    'url' => '/job/detail/' . $item->id,
                                    'classJobCus' => 'wFullJobItem',
                                ])
                            @endforeach
                            {{-- <h3>Related Blogs</h3>
                            @foreach ($blogRelates as $index => $item)
                                @include('website::components.blogItem', [
                                    'item' => $item,
                                    'url'=>'/job/detail/'.$item->id,
                                    'classJobCus' => "wFullBlogItem"
                                ])
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@stop
@section('script')
    <script></script>
@stop
