<div class="mobile-filters">
@foreach($filters as $filter)
    @php
    $dropdownId = uniqid('dropdown_');
    @endphp
    @if(!empty($filter['terms']))
    <div class="taxonomy-filters {{ $filter['taxonomy'] }}_filters">
        <div class="dropdown">
            @if(isset($filter['title']) && $filter['title'] !== '')
                <button class="btn btn-secondary dropdown-toggle" type="button" id="{{$dropdownId}}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {!! $filter['title'] !!}
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
            @endif
            <div class="dropdown-menu" aria-labelledby="{{$dropdownId}}">
                <ul class="accordion">
                    @foreach($filter['terms'] as $term)
                        @include('components.taxonomy-filter.filter-item', array_merge($term, ['input_name' => ( isset($term['taxonomy']) ? $term['taxonomy'] : $filter['taxonomy'] )]))
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
@endforeach
</div>
