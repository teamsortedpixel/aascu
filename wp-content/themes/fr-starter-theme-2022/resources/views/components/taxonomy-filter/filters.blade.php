<div class="desktop-filters">
@foreach($filters as $filter)
    @if(!empty($filter['terms']))
    <div class="taxonomy-filters {{ $filter['taxonomy'] }}_filters">
        @if(isset($filter['title']) && $filter['title'] !== '')
            <h6 class="title">{!! $filter['title'] !!}</h6>
        @endif
        <ul class="accordion">
            @foreach($filter['terms'] as $term)
                @include('components.taxonomy-filter.filter-item', array_merge($term, ['input_name' => ( isset($term['taxonomy']) ? $term['taxonomy'] : $filter['taxonomy'] )]))
            @endforeach
        </ul>
    </div>
    @endif
@endforeach
</div>