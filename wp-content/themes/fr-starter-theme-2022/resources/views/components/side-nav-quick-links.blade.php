@if ($is_enabled && isset($anchors) && is_array($anchors))
	<div class="side-bar-nav-links-container">
		<div class="side-bar-nav-links-inner">
			<button class="title-container toggle-btn main-toggler-btn">
				<div class="half-circle">
					@svg('images.quick-link-arrow')
				</div>
				<span>Quick Links</span>
			</button>
			<div class="list-container">
				<div class="list-continer-inner" role="navigation" aria-label="Quick Links Navigation">
					<button class="half-circle flipped toggle-btn">
						@svg('images.quick-link-arrow')
					</button>
					<ul class="list-group">
						@foreach ($anchors as $a)							
							<li>
								<a href="#{{ $a['anchor'] }}" class="list-group-item list-group-item-action">{!! $a['label'] !!}</a>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
@endif