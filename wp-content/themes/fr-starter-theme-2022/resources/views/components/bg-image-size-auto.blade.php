<div class="bg-image-size-auto {{ isset($class) ? $class : '' }}">
    <div style="width:max-content;height:max-content;{{ isset($margins) ? 'margin: '.$margins : ''}}">
        @include('components.responsive-acf-image', ['image' => $background_image, 'class' => 'row-bg-image desktop'])
    </div>
</div>