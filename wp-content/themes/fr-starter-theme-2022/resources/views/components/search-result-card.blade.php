<div class="fr-card general-search-result-card">
	<div class="card-inner {{ isset($image) ? 'has-image' : '' }}">
		@if ($program_tags)
			<div class="program-tags-container">
				{!! $program_tags !!}
			</div>
		@endif
		<div class="wysiwyg-content">
			<a class="no-dec" href="{{ $permalink ? $permalink['url'] : '' }}" target="{{ $permalink ? $permalink['target'] : '' }}"><h3>{!! $title !!}</h3></a>
			<p class="large">{!! $excerpt !!}</p>
		</div>
		@if ($image)
			@include('components.responsive-acf-image', ['image' => $image])
		@endif
	</div>
</div>