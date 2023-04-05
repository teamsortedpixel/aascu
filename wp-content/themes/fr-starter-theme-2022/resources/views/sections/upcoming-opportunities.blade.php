<div class="upcoming-opportunities-container requires-splidejs">
    <div class="upcoming-opportunities-overlay"></div>
    <div class="upcoming-opportunities-side-label"><span class="label">{{ $side_label }}</span><span class="arrow">@svg('images.arrow_small')</span></div>
    <section class="upcoming-opportunities-slide-panel">
        <div class="inner-container">
            <div class="side-label-hover"><span>{{ $side_label }}</span><span>@svg('images.arrow_small')</span></div>
            @if($event_section && !empty($events))
                <h4 class="section-label">{{ $event_section['title'] }}</h4>
                @include('sections.event-cards', ['event_posts' => $events, 'card_style' => 'condensed', 'column_layout' => 'column-3'])
                @include('sections.event-cards', ['event_posts' => $events, 'card_style' => 'condensed', 'column_layout' => 'column-3', 'is_slider' => true, 'classes' => 'mobile-view'])
            @endif
            @if(!empty($program_application_cards))
                <h4 class="section-label">{{ $program_application_section['title'] }}</h4>
                @include('sections.event-cards', ['cards' => $program_application_cards, 'card_style' => 'condensed-small', 'column_layout' => 'column-2'])
                @include('sections.event-cards', ['cards' => $program_application_cards, 'card_style' => 'condensed-small', 'column_layout' => 'column-2', 'is_slider' => true, 'classes' => 'mobile-view'])
            @endif
            @if(!empty($committee_openings_cards))
                <h4 class="section-label">{{ $committee_openings_section['title'] }}</h4>
                @include('sections.event-cards', ['cards' => $committee_openings_cards, 'card_style' => 'condensed-small', 'column_layout' => 'column-2'])
                @include('sections.event-cards', ['cards' => $committee_openings_cards, 'card_style' => 'condensed-small', 'column_layout' => 'column-2', 'is_slider' => true, 'classes' => 'mobile-view'])
            @endif
        </div>
    </section>
</div>