<style>
    .job-card {
        background-color: #fff;
        border-radius: 8px;
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
        border-radius: 8px;
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
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .view-job {
        background-color: #ff9900;
        color: #fff;
    }

    .apply {
        background-color: #ff9900;
        color: #fff;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
<!-- Job Card 1 -->
<div class="job-card">
    <img src="{{$item?->imageUrl}}" alt="Job Image" onerror="(this).src='{{ asset('https://via.placeholder.com/150') }}'">
    <div class="job-details">
        <h3>{{$item?->title ?? ''}}</h3>
        <p>{!!$item?->job_des ?? ''!!}</p>
        <span class="job-duration">3 - 6 months</span>
        <div class="job-buttons">
            <button class="btn view-job">View Job</button>
            <button class="btn apply">Apply</button>
        </div>
    </div>
</div>
