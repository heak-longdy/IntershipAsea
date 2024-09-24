@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::components.banner', [
            'header_name' => '',
            'imgUrl' => '../../website/img/ourService01.png',
            'text1' => 'Our Blogs',
            'text2' => 'Our blog is your go-to resource for everything related to internships in Cambodia.',
            'search' => 'disable',
            'clickhere'=>'disable'
        ])
        {{-- listJob --}}
        <div class="webContentListLayout">
            <div class="webContentList">

                <div class="blog-container">
                    <h2>Why Our Blog in Beneficial</h2>
                    <div class="whyOurBlog">
                        <div class="whyOurBlogItem">
                            <h3 class="fontWeight">In-Depth Information</h3>
                            <p>We strive to provide the highest
                                quality of service to our interns and
                                partner organizations, ensuring that
                                every internship experience is en-
                                riching and valuable.</p>
                        </div>
                        <div class="whyOurBlogItem">
                            <h3 class="fontWeight">Discover Our In-Country Partners</h3>
                            <p>We conduct our operations with
                                transparency and honesty, building
                                trust with our interns, partners, and
                                the communities we serve.</p>
                        </div>
                        <div class="whyOurBlogItem">
                            <h3 class="fontWeight">Answers to FAQs</h3>
                            <p>We believe in the power of working
                                together. By fostering strong part-
                                nerships with businesses, education-
                                al institutions, and government
                                agencies, we create opportunities
                                that are benecial for everyone
                                involved.</p>
                        </div>
                    </div>
                    <h2>Latest Blog Articles</h2>
                    <div class="blog-grid">
                        @foreach ($data as $index => $item)
                            @include('website::components.blogItem', [
                                'item' => $item,
                                'url' => '/job/detail/{{ $item?->id }}',
                            ])
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
