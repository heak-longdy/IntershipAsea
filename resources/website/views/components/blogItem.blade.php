
<!-- Blog Card 1 -->
<div class="blog-card {{ isset($classJobCus) ? $classJobCus : '' }}"">
    <div class="bgBlog"></div>
    <div class="blogImg">
        <img src="{{$item->image_url}}" alt="Blog Image">
    </div>
    <div class="blog-content">
        <h3>Exploring the Benefits of Interning in Cambodia</h3>
        <p>Discover the unique advantages of choosing Cambodia
            for your internship, from professional growth
            opportunities to cultural immersion experiences.</p>
        <div class="blog-footer">
            <span>February 3, 2023</span>
            <a href="{{url($url)}}">Read More</a>
        </div>
    </div>
</div>
