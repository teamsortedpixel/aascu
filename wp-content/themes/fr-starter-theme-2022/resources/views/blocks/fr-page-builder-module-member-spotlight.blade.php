<div class="{{ $block->classes }} module module-member-spotlight requires-splidejs" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	<div class="container-fluid">
		<div class="splide {{ isset($members) && is_array($members) && count($members) > 1 ? 'has-slides' : '' }}" @if($block->preview) style="visibility:visible;" @endif>
			<div class="member-spotlight-slider">
				<div class="splide__track">
					<div class="splide__list">
						@foreach ($members as $item)
							<div class="splide__slide">
								<div class="left-container">
                                    <div class="profile-image">
										@if ($item['member_image'] && is_array($item['member_image']))
											<x-responsive-acf-image class="image-circle" :image="$item['member_image']" />
										@endif
									</div>
								</div>
								<div class="right-container">
									<div class="member-content">{!! $item['member_content'] !!}</div>

									<h5>
										{{ $item['impact_title'] }}
									</h5>

									<div class="member-impact-details">
										@if ($item['member_impact'])
											@foreach ($item['member_impact'] as $impact_details)
												<div class="imapct-content">
													{!! $impact_details['impact_content'] !!}
												</div>
											@endforeach
										@endif
									</div>

								</div>
							</div>
						@endforeach
					</div>
				</div>
				@if (isset($members) && is_array($members) && count($members) > 1)                    
					<div class="splide__arrows splide__arrows--ltr">
						<div class="splide__arrows arrow--left-side">
							<button class="splide__arrow splide__arrow--prev">
								<div class="badge"></div>
							</button>
						</div>
						<div class="splide__arrows arrow--right-side">
							<button class="splide__arrow splide__arrow--next">
								<div class="badge"></div>
							</button>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
