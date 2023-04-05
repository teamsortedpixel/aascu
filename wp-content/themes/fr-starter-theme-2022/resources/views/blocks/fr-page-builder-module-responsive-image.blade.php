<div class="module responsive-image-module {{ $block->classes }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	<picture>
		@if ($image_desktop && is_array($image_desktop))
			<source media="(min-width: 767px)" srcset="{{ isset($image_desktop['id']) ? wp_get_attachment_image_srcset($image_desktop['id'], 'full') : $image_desktop['url'] }}">
		@endif
		@if ($image_mobile && is_array($image_mobile))
			<source media="(max-width: 768px)" srcset="{{ isset($image_mobile['id']) ? wp_get_attachment_image_srcset($image_mobile['id'], 'full') : $image_mobile['url'] }}">
		@endif
		@if (($image_desktop && is_array($image_desktop)) || ($image_mobile && is_array($image_mobile)))
			<img class="fr-responsive-image" src="{{ is_array($image_desktop) && $image_desktop['url']? $image_desktop['url'] : $image_mobile['url'] }}" alt="{{ @$image_desktop['alt'] ||  @$image_mobile['alt'] }}">
		@endif
	</picture>
</div>