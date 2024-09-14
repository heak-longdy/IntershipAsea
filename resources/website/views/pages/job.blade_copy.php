<style>
.jobLayout{
    display: flex;
    justify-content: center;
    flex-direction: column;
}
.jobLayout>.jobGp{
    margin: 30px;
    width: 1200px;
}
.jobLayout>.jobGp>.jobItem{
    display: flex;
    grid-gap: 20px;
    margin-bottom: 20px;
}
.jobLayout>.jobGp>.jobItem>.jobLeft>.imgGp{
    width: 350px;
    height: fit-content;
    border-radius: 24px;
    overflow: hidden;
    height: 225px;
    object-fit: cover;
}
.jobLayout>.jobGp>.jobItem>.jobLeft>.imgGp>img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.jobLayout>.jobGp>.jobItem>.jobRight{

}
.jobLayout>.jobGp>.jobItem>.jobRight>.jobText{

}
.jobLayout>.jobGp>.jobItem>.jobRight>.jobText>.link-post{
    font-weight: 600;
    color: var(--black);
    font-size: 20px;
}
.viewMore{
    margin: 30px;
    width: 1200px;
    display: flex;
    justify-content: center;
}
.viewMore>a>div{
    width: fit-content;
    height: 40px;
    background: #9e9e9e00;
    align-items: center;
    display: flex;
    padding: 0 15px;
    border-radius: 10px;
    border: 1px solid #80808054;
}
</style>
<div class="jobLayout">
    <div class="jobGp">
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
    </div>
    <div class="viewMore">
       <a href=""><div>See More<i class='bx bx-chevron-right'></i></div></a>
    </div>
</div>

<h1>Enter Image URL</h1>
    <form id="imageForm" method="POST" action="{{ route('image.upload') }}">
        @csrf
        <input type="text" id="imageURL" name="imageURL" placeholder="Enter image URL here">
        <button type="submit">Upload Image</button>
    </form>