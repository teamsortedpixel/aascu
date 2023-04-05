<div class="module featured-resources-module {{ $block->classes }}" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
	<div class="container">
		<div class="row">
			<div class="col">
				@if ($title)
					<div class="featured-opp-title"><span>{!! $title !!}</span></div>
				@endif
				<ul class="nav nav-pills featured-opp-nav-tabs" id="{{ $module_id }}" role="tablist">
					@foreach ($items as $i => $item)
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $i == 0 ? 'active' : '' }} featured-opp-nav-buttons"
								id="{{ $module_id }}-{{ $i }}-tab" data-bs-toggle="pill"
								data-bs-target="#{{ $module_id }}-{{ $i }}" type="button" role="tab"
								aria-controls="{{ $module_id }}-{{ $i }}"
								aria-selected="true">{{ $item['tab_title'] ?? '' }}</button>
						</li>
					@endforeach
				</ul>
				<div class="tab-content" id="{{ $module_id }}-content">
					@foreach ($items as $i => $item)
						<div class="tab-pane fade {{ $i == 0 ? 'active show' : '' }} featured-opp-content wysiwyg-content"
							id="{{ $module_id }}-{{ $i }}" role="tabpanel"
							aria-labelledby="{{ $module_id }}-{{ $i }}-tab">
							<div class="featured-opp-tab-content">
								<h2>{!! $item['title'] !!}</h2>
								@isset($item['permalink'])
									<x-cta-button :label="$item['permalink']['label']" type="external_url" :new-tab="false" :external-url="$item['permalink']['url']" />
								@endisset
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col">
				<div class="tab-content tab-content-images" id="{{ $module_id }}-content-images">
					@foreach ($items as $i => $item)
						<div class="tab-pane fade {{ $i == 0 ? 'active show' : '' }} featured-opp-content wysiwyg-content" id="{{ $module_id }}-{{ $i }}-image" role="tabpanel" aria-labelledby="{{ $module_id }}-{{ $i }}-tab-image">
							@if ($item['image'])
								<div class="featured-opp-tab-image">
									<div class="tab-image-aspect-ratio-box">
										<div class="tab-image-aspect-ratio-box-inner">
											<x-responsive-acf-image :image="$item['image']" size="large" />
										</div>
										<span class="featured_opp_tab_below_content">{!! $item['image']['caption'] !!}</span>
									</div>
								</div>
							@endif
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
