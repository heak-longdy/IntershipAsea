<div class="jobLayout" style="background: url('../../website/img/homeProgram.png');background-size: cover;">
    <div class="jobListing">
        <h3 class="jobTitle">Intership Programmer</h3>
        <div class="jobContainer">
            @foreach ($jobs as $index => $item)
                @include('website::components.jobItem',['item' => $item])
            @endforeach
        </div>
    </div>
    {{-- <div class="jobGp">
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src="{{asset('images/1_rangerover_tracking.jpg')}}" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src="https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2023/04/949086925-2023-apr-18-14-29-52-000000-Teaching.jpg" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src=" https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2020/07/1807660963-2020-jul-28-15-26-55-000000-diving-689831_1920-1.jpg" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src="https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2023/04/1334408287-2023-apr-18-14-29-33-000000-Community-Development.jpg" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src="https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2023/04/876644367-2023-apr-18-14-29-56-000000-Women_s-Empowerment.jpg" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
        <div class="jobItem">
            <div class="jobLeft">
                <div class="imgGp">
                    <img src="https://assets.gviworld.com/cdn-cgi/image/gravity=0.5x0.5,format=auto,quality=100/https://cdnp.gvi.co.uk/wp-content/uploads/2023/04/1920903692-2023-apr-18-14-29-49-000000-Public-Health.jpg" />
                </div>
            </div>
            <div class="jobRight">
                <div class="jobText">
                    <a href="#" class="link-post">How To Create Admin Dashboard With HTML CSS And Javascript</a>
                    <p>2024-12-20</p>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="viewMore">
        <a href="">
            <div>See More<i class='bx bx-chevron-right'></i></div>
        </a>
    </div>
</div>
