<div class="social-wrapper">
	@forelse ($social_links as $l)
		<a class="alt-btn" href="{{ $l['url']?: '#' }}" target="_blank" title="{{ $l['type'] ? 'Follow us on ' . $l['type']['label'] : '' }}" aria-label="{{ $l['type'] ? 'Follow us on ' . $l['type']['label'] : '' }}">
			@if ($l['type'])
				@svg('images.'.$l['type']['value'])
				{{ $l['type']['label'] }}
			@endif
		</a>
	@empty
	@endforelse
</div>