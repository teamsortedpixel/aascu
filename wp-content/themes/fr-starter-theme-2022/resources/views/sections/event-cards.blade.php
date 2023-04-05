@php
$is_slider = @$is_slider && count($events) > 1
@endphp

<div class="event-cards {{ @$is_slider?'event-cards-slider splide':'' }} {{ @$classes }}">
    
    @if(@$is_slider)<div class="splide__track"><div class="splide__list">@endif
        @if(!empty($cards))    
            @foreach($cards as $card) 
                <x-card :cardData="$card" :style="$card_style" classes="{{ @$is_slider?'splide__slide':'' }} {{ $column_layout }}"/>
            @endforeach
        @elseif(!empty($event_posts))    
            @foreach($event_posts as $event) 
                <x-card :post="$event['id']" :style="$card_style" classes="{{ @$is_slider?'splide__slide':'' }} {{ $column_layout }}"/>
            @endforeach
        @endif
    @if(@$is_slider)
        </div>
    </div>
    <div class="arrows-container">
        <div class="splide__arrows arrow--left-side">
            <button class="splide__arrow splide__arrow--prev">
                <div class="badge badge-dark-blue"></div>
            </button>
        </div>
        <div class="splide__arrows arrow--right-side">
            <button class="splide__arrow splide__arrow--next">
                <div class="badge badge-dark-blue"></div>
            </button>
        </div>
    </div>
    @endif
</div>