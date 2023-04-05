<div class="module video-embed-caption-module {{ $block->classes }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	<div class="container-fluid">
		<div class="row">
			<div class="col wysiwyg-content">
				@if ($video)
					<div class="wp-caption aligncenter">
						{!! $video !!}
						@if (isset($caption) && strlen($caption))
							<p class="wp-caption-text">{!! $caption !!}</p>
						@endif
					</div>
				@endif
			</div>
		</div>
	</div>
</div>