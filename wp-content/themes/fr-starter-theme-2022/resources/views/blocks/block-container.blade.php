<div class="fr-content-row {{ $block->classes }} {{ $background_color }} vert-pad-{{ $vertical_padding }} vert-stack-{{ $vertically_stack_content }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	@if ($background_image || $background_image_mobile)
		@if ($background_image_size == 'contain')
			@include('components.responsive-acf-image', ['image' => $background_image, 'class' => 'row-bg-image desktop'])
		@else
			@include('components.bg-image-size-auto', ['image' => $background_image, 'class' => 'hide-on-mobile', 'margins' => $background_image_dimensions])
		@endif

		@if ($background_image_mobile_size == 'contain')
			@include('components.responsive-acf-image', ['image' => $background_image_mobile, 'class' => 'row-bg-image mobile'])
		@else
			@include('components.bg-image-size-auto', ['image' => $background_image_mobile, 'class' => 'hide-on-desktop', 'margins' => $background_image_dimensions])
		@endif

		@if ($background_color_overlay)
			<div class="row-bg-overlay" {!! $background_color_overlay !!}></div>
		@endif
	@endif

	@isset($circles)
		<div class="circle-decoration-container">
			<div class="circle-decoration-inner">
				@foreach ($circles as $c)
					<x-circle-decoration :diameter="$c['size']" :diameter-mobile="$c['size_mobile'] ?? false" :style="$c['style']" :position="$c['position']" />
				@endforeach
			</div>
		</div>
	@endisset

	<div class="container {{ $content_max_width }} {{ $custom_max_width_class }}" {!! $container_atts !!}>
		@if ($block->preview)
			<div class="fr-empty-slot empty-slot-content-section">
				<i>Edit “Block Container” settings on <a href="javascript:void(0)" fr-open-sidebar-btn>Sidebar Settings <span></span></a> panel. <br>Click the Appender <span class="appender-icon"></span> below to add Content Blocks, or add “Free Range Columns” to create new row structures.</i>
			</div>
		@endif
		<InnerBlocks allowedBlocks='{{ $allowed_blocks }}' />
	</div>
</div>
