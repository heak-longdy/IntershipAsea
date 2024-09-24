<style>
    .job-card {
        background-color: #fff;
        border-radius: 15px;
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
        /* border: 1px solid #9e9e9e1c; */
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        overflow: hidden;
        padding: 20px;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }

    .job-card img {
        width: 240px;
        height: 165px;
        object-fit: cover;
        border-radius: 15px;
        /* margin-right: 20px; */
    }

    .job-details {
        flex: 1;
        margin-left: 20px;
    }

    .job-details h3 {
        font-size: 20px;
        color: #333;
        margin: 0 0 5px;
    }

    .job-details p {
        color: #666;
        /* margin: 0 0 10px; */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: initial;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .job-duration {
        display: block;
        font-size: 14px;
        color: #999;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .job-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;

    }

    .btnJob {
        /* padding: 10px 20px; */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        padding: 8px 15px 8px 10px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        grid-gap: 7px;
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

    .job-btn-left {
        grid-gap: 15px;
        display: flex;
        align-items: center;
    }

    .job-btn-left>a {
        text-decoration: unset;
    }
</style>
<!-- Job Card 1 -->
<div class="job-card">
    <img src="{{ $item?->imageUrl }}" alt="Job Image"
        onerror="(this).src='{{ asset('https://via.placeholder.com/150') }}'">
    <div class="job-details">
        <div
            style="    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 5px;
    margin-top: -5px;
">
            <h3>{{ $item?->title ?? '' }}</h3>
            <div
                style="    background: #49b70d;
    color: #fff;
    border-radius: 0.75rem;
    padding: 3px 15px;
    white-space: nowrap;
    margin-top: 5px;">
                Full Time
            </div>
        </div>
        <div style="display: flex;
    align-items: center;
    margin-bottom: 10px;
    grid-gap: 10px;"
            class="fontWeight">
            <p class="colorYellow">$90.00</p>
        </div>

        <p>{!! $item?->job_des ?? '' !!}</p>
        {{-- <span class="job-duration">3 - 6 months</span> --}}
        <div class="job-buttons">

            <div class="job-btn-left">
                <a href="{{ route('web-apply-form') }}">
                    <button type="button" class="btnJob apply"><i
                            class='bx bx-right-top-arrow-circle bx-fade-right-hover'></i><span>Apply Now</span></button>
                </a>
                <a href="{!! isset($urlDetail) ? url($urlDetail) : '#' !!}">
                    <button class="btnJob view-job"><i class='bx bx-show'></i><span>View Job</span></button>
                </a>
            </div>
            <p class="">3 - 6 months</p>
        </div>
    </div>
</div>
