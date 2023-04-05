@if(!empty($header_filters))
<div class="header-filters-container">
    <form action="#" method="get" class="header-form">
        <ul class="header-filters">
            @foreach($header_filters as $filter)
                @include('components.taxonomy-filter.header-filter-item', $filter)
            @endforeach
        </ul>
    </form>
</div>
@endif