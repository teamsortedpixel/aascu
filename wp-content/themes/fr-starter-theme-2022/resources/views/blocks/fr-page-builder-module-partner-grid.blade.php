<div class="{{ $block->classes }} module partner-grid-module" @isset($block->block->anchor) id="{{ $block->block->anchor }}" @endif>
	<div class="container-fluid partner-grid-wrapper {{ $style ?? 'style-1' }}">
		@forelse ($logo_grid as $group)
			@switch($group['acf_fc_layout'])
				@case('partner_grid_logo_group_layout')
					<div class="logo-group {{ is_array($group['logos']) ? 'logo-count-' . (count($group['logos']) >= 4 ? '4' : count($group['logos'])) : '' }}">
						<div class="logo-group-title wysiwyg-content">@if(isset($group['title']) && strlen($group['title']))
							<h4>{!! $group['title'] !!}</h4>
						@endif</div>
						<div class="logo-group-logos-container">
							@foreach ($group['logos'] ?: [] as $logo)
								<div class="logo-item">
									@if (isset($logo['logo']) && is_array($logo['logo']))
										<a href="{{ is_array($logo['link']) ?  $logo['link']['url'] : '#' }}" target="{{ is_array($logo['link']) ? $logo['link']['target'] : '' }}" title="{{ is_array($logo['link']) ? $logo['link']['title'] : '' }}">
											<x-responsive-acf-image :image="$logo['logo']" size="full"/>
										</a>
									@endif
									@if (isset($logo['description']) && $style == 'style-2')
										<div class="description-container wysiwyg-content">{!! $logo['description'] !!}</div>
									@endif
								</div>
							@endforeach
						</div>
					</div>
					@break
				@default
			@endswitch
		@empty
			{{-- Nothing to see here --}}
		@endforelse
	</div>
</div>
