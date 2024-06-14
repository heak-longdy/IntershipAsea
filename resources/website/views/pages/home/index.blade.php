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
    </div>
@stop
@section('script')

@stop
