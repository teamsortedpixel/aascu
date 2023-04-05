<a href="{{ $permalink['url'] }}" target="{{ $permalink['target']?:'_self' }}" class="fr-card {{ $classes }} type-{{ $post_type }} condensed-small">
	<div class="card-inner">
		<div class="date-container">
			<p class="date">
				<span>{!! $formatted_date !!}</span>
			</p>
		</div>
		<div class="info-wrapper">
			<div class="title-wrapper not--truncated" title="{{ wp_specialchars_decode($title) }}">
				<div fr-truncate-lines="2">{!! $title !!}</div>
			</div>
			@if ($formatted_time)
				<div class="desc">
					<p>{{ $formatted_time }}</p>
				</div>
			@endif
		</div>
	</div>
</a>