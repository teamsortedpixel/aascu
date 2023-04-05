<section class="hero fr-hero hero-section fr-content-row section-bg-deep-navy {{ $type ? $type.'-page-hero' :'' }} {{ !empty($blockData)? $blockData->classes:'' }} {{ $image ? 'with-image':'without-image'}}">
	@isset($circles)
		<div class="circle-decoration-container">
			<div class="circle-decoration-inner">
				@foreach ($circles as $c)
					<x-circle-decoration :diameter="$c['size']" :diameter-mobile="$c['size_mobile'] ?? false" :style="$c['style']" :position="$c['position']" />
				@endforeach
			</div>
		</div>
	@endisset
	<div class="container hero-container {{ $image ? 'with-image':'without-image'}}">
		<div class="row-top">
			<div class="left-content">
				@if($format || $formattedDate || $program_tags || $publicationDate)
					<div class="hero-header">
						@if($program_tags && !in_array($type, ['front', 'event']))
							<div class="programs">
								{!! $program_tags !!}
							</div>
						@endif
						@if(!empty($publicationDate) && !$hideDate)<span class="date">{!! $publicationDate !!}</span>@endif
						@if(!empty($formattedDate))<span class="date">{!! $formattedDate !!}</span>@endif
						@if(!empty($formattedTime) || $format)
						<div class="header-right">
							@if(!empty($formattedTime))<span class="time">{{ $formattedTime }}</span>@endif
							@if($format)
							<div class="format-container">
								<span class="badge program-badge">{!! $format['name'] !!}</span>
							</div>
							@endif
						</div>
						@endif
					</div>
				@endif
				<div class="wysiwyg-content">
					{!! $content !!}
				</div>
				@if(!empty($author))<span class="author">{!! $author !!} </span>@endif
			</div>
			@if($image)
			<div class="right-image">
				<div class="hero-image-aspect-ratio-wrapper">
					<x-responsive-acf-image :image="$image" size="large" class="hero-image" />
				</div>
				@if($imageCaption && $imageCaption !== '')<span class="caption">{{ $imageCaption }}</span>@endif
			</div>
			@endif
		</div>
		@if(!empty($buttons) && count($buttons) > 0)
			<div class="row row-bottom">
				<div class="col wysiwyg-content buttons-container">
					@foreach ($buttons as $i => $button)
						<x-cta-button :label="$button['label']" :type="$button['cta_type']" :post-id="$button['post_id']" :style="$button['style']" :external-url="@$button['external_url']" :new-tab="@$button['new_tab']" />
					@endforeach
				</div>
			</div>
		@endif
	</div>
	
</section>