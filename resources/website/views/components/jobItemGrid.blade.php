
<!-- Blog Card 1 -->
<div class="job-card-grid {{ isset($classJobCus) ? $classJobCus : '' }}"">
    <div class="bgJob"></div>
    <div class="jobImg">
        <img src="{{$item->image_url}}" alt="Job Image">
    </div>
    <div class="job-card-grid-content">
        <h3>Exploring the Benefits of Interning in Cambodia</h3>
        <p>Discover the unique advantages of choosing Cambodia
            for your internship, from professional growth
            opportunities to cultural immersion experiences.</p>
        <div class="job-card-grid-footer">
            {{-- <span>February 3, 2023</span> --}}
            {{-- <a href="{{url($url)}}">Read More</a> --}}
            <a href="{{ route('web-apply-form') }}">
                <button type="button" class="btnJob apply"><i
                        class='bx bx-right-top-arrow-circle bx-fade-right-hover'></i><span>Apply
                        Now</span></button>
            </a>
            <a href="{{ url($url) }}">
                <button type="button" class="btnView btnJob"><i class='bx bx-show'></i><span>View Job</span></button>
            </a>
        </div>
    </div>
</div>
