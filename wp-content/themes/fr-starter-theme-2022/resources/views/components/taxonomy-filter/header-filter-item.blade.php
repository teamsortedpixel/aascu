<li class="filter-item">
    <div class="form-field checkbox-field">
        <label for="{{ $input_name }}_{{ $value }}">
            <input type="radio" id="{{ $input_name }}_{{ $value }}" name="{{ $input_name }}[]" value="{{ $value }}" data-filter_name="{{ $input_name }}">
            <span class="checkmark"></span>
            <span class="label wysiwyg-content"><p>{!! $label !!}</p></span>
        </label>
    </div>
</li>