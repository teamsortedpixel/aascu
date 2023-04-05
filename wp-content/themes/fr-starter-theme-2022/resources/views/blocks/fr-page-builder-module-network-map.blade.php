<div class="module network-map-module {{ $block->classes }}"@if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif fr-module-id="{{ $module_id }}" map-module-data='{{ wp_json_encode($data) }}'>
    <div class="container">
		<div class="row network-map-container">
			<div class="col col-no-pad">
				<div class="mobile-zoom-buttons">
					<div role="button" data-zoom-in>+</div>
					<div role="button" data-zoom-out>-</div>
				</div>
				<div class="network-map-layer">
					<div class="network-map-layer-inner">
						<div class="map-aspect-ratio-container">
							@svg('images.network-map-1')
							<div class="state-label" style="left:93.25%;top:60.123%;display:block;" state-label-abv="GU">
								<span>
									<b>Guam</b>
								</span>
							</div>
							<div class="state-label" style="left:93.25%;top:80.800%;display:block;" state-label-abv="PR">
								<span>
									<b>Puerto Rico</b>
								</span>
							</div>
							<div class="state-label" style="left:93.25%;top:99.999%;display:block;" state-label-abv="VI">
								<span>
									<b>U.S. Virgin Islands</b>
								</span>
							</div>
							<div class="state-label w-circle" style="top:99.650%;left:0;width:3.39172%;height:5.13811%;" state-abv="Bahamas">
								<div class="circle">
									<div class="tooltip-container"></div>
								</div>
								<span>
									<b>Bahamas</b>
								</span>
							</div>
							<div class="state-label w-circle" style="top:99.650%;left:17%;width:3.39172%;height:5.13811%;" state-abv="Canada">
								<div class="circle">
									<div class="tooltip-container"></div>
								</div>
								<span>
									<b>Canada</b>
								</span>
							</div>
							<div class="state-label w-circle" style="top:99.650%;left:33.300%;width:3.39172%;height:5.13811%;" state-abv="Mexico">
								<div class="circle">
									<div class="tooltip-container"></div>
								</div>
								<span>
									<b>Mexico</b>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row tooltip-container-mobile"></div>
		@isset($map_styles)
			{!! $map_styles !!}
		@endisset
		<div class="network-map-tooltip fade requires-simplescrollbarjs">
			<div class="network-map-tooltip-inner">
				<div class="title-container">
					<H4></H4>
				</div>
				<div class="list-container">
					<ul></ul>
				</div>
			</div>
		</div>
		@if (isset($error_msg) && $error_msg)
			<div class="row" style="max-width:600px; background:#18171B; padding:15px;">
				<p style="color:#56DB3A;font-size:12px;margin-bottom:0;"><b>Admin notice! Error found:</b></p>
				@dump($error_msg)
				<p style="color:#56DB3A;font-size:12px;margin-bottom:0;">Please check the API data provided on Theme Settings -> API Settings, and click Clear all API Caches.</p>
			</div>
			@endif
	</div>
</div>