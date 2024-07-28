@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        <div class="wb-item-layout">
            @foreach ($data as $item)
                <div class="wb-item">
                    <div class="item">
                        <div class="img-gp">
                            {{-- <img src="{{ asset('images/logo/emptyData.png') }}" /> --}}
                            <img src="https://5play.ru/uploads/posts/2023-03/1677998865_1.webp" />
                            
                        </div>
                        <div class="text-gp">
                            <label>{{ Str::limit(($item->bookingDetail[0]->product ? $item->bookingDetail[0]->product?->name : $item->bookingDetail[0]->service?->name), 30) }}</label>
                            <p>Sony Z1</p>
                            <p>$&nbsp;50.00</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="wb-item">
                <div class="item">
                    <div class="img-gp">
                        <img src="{{ asset('images/logo/emptyData.png') }}" />
                    </div>
                </div>
            </div>
            <div class="wb-item">
                <div class="item">
                    <div class="img-gp">
                        <img src="{{ asset('images/logo/emptyData.png') }}" />
                    </div>
                </div>
            </div>
            <div class="wb-item">
                <div class="item">
                    <div class="img-gp">
                        <img src="{{ asset('images/logo/emptyData.png') }}" />
                    </div>
                </div>
            </div>
            <div class="wb-item">
                <div class="item">
                    <div class="img-gp">
                        <img src="{{ asset('images/logo/emptyData.png') }}" />
                    </div>
                </div>
            </div>
        </div>

        <form action="{!! route('/apply') !!}" method="POST">
            @csrf
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
    
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br><br>
    
            <label for="cover_letter">Cover Letter:</label><br>
            <textarea id="cover_letter" name="cover_letter" required></textarea><br><br>
    
            <button type="submit">Submit</button>
        </form>
    </div>
@stop
@section('script')

@stop
