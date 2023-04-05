<div class="event-cards event-cards-slider splide {{ $column_layout }}">
    <div class="splide__track">
        <div class="splide__list">
        @if(!empty($events))    
            @foreach($events as $event) 
                @if($card_layout === 'vertical')
                    <x-card :post="$event['id']" style="condensed" classes="splide__slide"/> 
                @else
                    <x-card :post="$event['id']" style="condensed-small" classes="splide__slide"/> 
                @endif
            @endforeach
        @endif
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
</div>