@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        @include('website::components.banner', ['header_name' => ''])

        <div class="webContentListLayout marginTop_Bot30">
            <div class="webContentList">
                <div class="itemDetailLayout">
                    <div class="itemDetailGp">
                        <div class="itemDetailLeft">
                            <div class="itemDetailTitle">
                                <h3>Exploring the Benefits of Interning in Cambodia</h3>
                                {{-- <a href="">
                                    <button type="button" class="btnApply">Apply</button>
                                </a> --}}
                            </div>
                            <div class="itemDetailBody">
                                <div style="white-space: pre-line;">
                                    <h3>Exploring the Benefits of Interning in Cambodia</h3>
                                    <p>Ok, so let’s break down how you open a bank account in Cambodia. There are a
                                        couple of key points within this list so either write them down (which no-one does)
                                        or
                                        get in touch with us to make your move seamless.
                                        Here are the steps you should take to open a bank account in Cambodia:
                                        Gather the necessary documents: To open a bank account in Cambodia, you will need
                                        to provide a valid passport, a copy of your work permit or visa, and proof of
                                        address,
                                        such as a utility bill.
                                        Choose a bank: Cambodia has several major banks to choose from, of which we rec-
                                        ommend ABA, Acleda, and Canadia Bank. including Acleda Bank. Each bank may have
                                        dierent requirements, so it’s a good idea to research which bank best suits your
                                        needs.
                                        Open an account: Once you have gathered all the necessary documents, you can go</p>
                                    <img src="{{ asset('website/img/ourService01.png') }}" alt=""
                                        class="border-radius" style="width: 100%;">
                                    <p>If you are moving to Cambodia for work or otherwise, you can view our relocation ser-
                                        vices here or get in touch with one of our expert team for advice on the best
                                        options
                                        when in-country.</p>
                                    <h3>Exploring the Benefits of Interning in Cambodia</h3>
                                    <p>Ok, so let’s break down how you open a bank account in Cambodia. There are a
                                        couple of key points within this list so either write them down (which no-one does)
                                        or
                                        get in touch with us to make your move seamless.
                                        Here are the steps you should take to open a bank account in Cambodia:
                                        Gather the necessary documents: To open a bank account in Cambodia, you will need
                                        to provide a valid passport, a copy of your work permit or visa, and proof of
                                        address,
                                        such as a utility bill.
                                        Choose a bank: Cambodia has several major banks to choose from, of which we rec-
                                        ommend ABA, Acleda, and Canadia Bank. including Acleda Bank. Each bank may have
                                        dierent requirements, so it’s a good idea to research which bank best suits your
                                        needs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="itemDetailRight">
                            <h3>Related Articles</h3>
                            @foreach ($blogRelates as $index => $item)
                                @include('website::components.blogItem', [
                                    'item' => $item,
                                    'url' => '/blog/detail/' . $item->id,
                                    'classJobCus' => 'wFullBlogItem',
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
                    <div class="RelateItemLayout">
                        <h3>Related Jobs</h3>
                        <div class="RelateItemGp">
                            @foreach ($blogRelates as $index => $item)
                                @include('website::components.blogItem', [
                                    'item' => $item,
                                    'url' => '/blog/detail/' . $item->id,
                                    'classJobCus' => 'relateItem',
                                ])
                            @endforeach
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
