@php
    $collapse_id = 'collapse_'.uniqid();
    $label_id = 'label-'.uniqid();
    $input_name = strpos($input_name, '_tax') ? $input_name : $input_name.'_tax';
@endphp
<li class="accordion-item {{ $input_name }}_{{ $slug }}">
    <div class="form-field checkbox-field">
        <label for="{{ $input_name }}_{{ $slug }}" class="checkcontainer">
            <input type="checkbox" id="{{ $input_name }}_{{ $slug }}" name="{{ $input_name }}[]" value="{{ $slug }}" data-filter_name="{{ $input_name }}">
            <span class="checkmark"></span>
            <span class="checkcontainer" >{!! $name !!}</span>
        </label>
    </div>
    @if(!empty($childrens))
    <div id="{{ $collapse_id }}" class="accordion-collapse">
        <ul>
            @foreach($childrens as $child_term)
                @include('components.taxonomy-filter.filter-item', array_merge($child_term, ['input_name' => ( isset($child_term['taxonomy']) ? $child_term['taxonomy'] : $input_name )]))
            @endforeach
        </ul>
    </div>
    @endif
</li>
