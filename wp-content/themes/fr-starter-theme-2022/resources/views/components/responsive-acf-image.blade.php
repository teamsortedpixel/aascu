@if (isset($image) && $image)
	@if (isset($parameter_is_id) && $parameter_is_id)
		@php $img_id = $image @endphp
	@else
		@php $img_id = isset($image['ID']) ? $image['ID'] : false @endphp
	@endif
	@if (isset($img_id) && $img_id)
		{!! wp_get_attachment_image($img_id, isset($size) && $size ? $size : 'full', false, ['class' => isset($class) ? $class : '']) !!}
	@else
		<img src="{{ $image['url'] }}" class="{{ isset($class) ? $class : '' }}" alt="{{ isset($image['alt']) ? $image['alt'] : '' }}" loading="lazy">
	@endif
@endif