<div class="module card-grid-module {{ $block->classes }} {{ isset($extra_css) ? $extra_css : '' }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	<div class="container-fluid">
		@if ($cards)
			<div class="cards-inner card-grid-container {{ $use_slider ? 'has-slider requires-splidejs' : '' }}">
				@if ($use_slider)
					<section class="splide card-grid-slider" aria-label="Card Grid Slider" @if ($block->preview)
					style="visibility:visible;"
					@endif>
						<div class="splide__arrows">
							<button class="splide__arrow splide__arrow--prev" aria-label="Previous Slide" title="Previous Slide">
								<b>@svg('images.chevron')</b>
							</button>
							<button class="splide__arrow splide__arrow--next" aria-label="Next Slide" title="Next Slide">
								<b>@svg('images.chevron')</b>
							</button>
						</div>
						<div class="splide__track">
							<ul class="splide__list">
								@foreach ($cards as $c)
									<li class="splide__slide">{!! $c !!}</li>
								@endforeach
							</ul>
						</div>
					</section>
				@else
					@foreach ($cards as $c)
						{!! $c !!}
					@endforeach
				@endif
			</div>
		@endif
	</div>
</div>