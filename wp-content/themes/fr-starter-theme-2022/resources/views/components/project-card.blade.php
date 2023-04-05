<a href="{{ $preview ? 'javascript:void(0)' : $url }}" target="{{ $url_target }}" class="fr-card project-card">
    @if(isset($image_id) && $image_id)
	@include('components.responsive-acf-image', ['image' => $image_id, 'parameter_is_id' => true, 'size' => 'large', 'class' => 'card-img'])
    @else
        @if ($image_url)
            <img src="{{ $image_url }}" alt="{{ $image_alt }}" class="card-img">
        @endif
    @endif
    <div class="card-inner">
        @if ($tag)
            <div class="tag">{{ $tag }}</div>
        @endif
    </div>
    <div class="card-info-wrapper glass-white">
        <div class="wysiwyg-content">
            <h5>{!! $title !!}</h5>
            <p class="requires-ellipsis fr-truncate-text" fr-truncate-lines="3" >{!! $description !!}</p>
            <b class="badge arrow-right"></b>
        </div>
    </div>
</a>