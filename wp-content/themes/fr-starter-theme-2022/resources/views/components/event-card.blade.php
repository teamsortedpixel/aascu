<div class="fr-card event-card {{ isset($card_class)? $card_class :'' }}">
    <div class="date-container">
        <h3 class="date">{!! $event_data['date'] !!}</h3>
    </div>
    <div class="content-container">
        <h4 class="title">{!! $event_data['title'] !!}</h4>
        <div class="content-footer">
            <span class="time">{!! $event_data['time'] !!}</span>
            @if(isset($event_data['format']) && !empty($event_data['format']))<div class="format-container"><span class="format">{{ $event_data['format']['name'] }}</span></div>@endif
        </div>
    </div>
</div>