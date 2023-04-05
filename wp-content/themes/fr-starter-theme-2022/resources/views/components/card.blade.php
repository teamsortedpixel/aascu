<div class="fr-card {{ isset($classes) ? $classes : '' }} type-{{ $post_type }} {{ $image ? 'has-image' : '' }} {{ !empty($resource_type) && $resource_type['icon'] ? 'has-icon' : '' }} {{ $style ?? 'card-type-regular' }}"
	@if (in_array($post_type, ['event']) && $style == 'condensed')
		role="link" onclick="window.open('{{ $permalink['url'] }}', '{{ $permalink['target'] ?: '_self' }}');"
	@endif>

	<div class="card-inner" @if (in_array($post_type, ['page', 'news']) && $program && $style == 'card-type-regular')
		style="border-color:{{ $program['accent_color'] }};"
	@endif>
		@if (in_array($post_type, ['news', 'event']) && $image && $style == 'card-type-regular')
			<div class="card-image" @if (in_array($post_type, ['news', 'event']) && $program)
				style="border-color:{{ $program['accent_color'] }};"
			@endif>
				<x-responsive-acf-image :image="$image" size="thumbnail" />
			</div>
		@endif
		@if ($post_type == 'resource' && isset($resource_type['icon']))
			<div class="resource-image">
				{!!  $resource_type['icon_html'] !!}
			</div>
		@endif
		<div class="title-wrapper not--truncated" @if (in_array($post_type, ['page', 'news']) && $program && $style == 'card-type-regular')
			style="background-color:{{ $program['accent_color'] }}; color:{{ $program['text_color'] }};"
		@endif title="{{ wp_specialchars_decode($title) }}">
			@if (!empty($permalink) && !(in_array($post_type, ['event']) && $style == 'condensed'))
			<a fr-truncate-lines href="{{ $permalink['url'] }}" target="{{ $permalink['target'] ?: '_self' }}">{!! $title !!}</a>
			@else
			<div fr-truncate-lines>{!! $title !!}</div>
			@endif
			
		</div>
		@if (!in_array($post_type, ['resource', 'event']) && $excerpt && $style == 'card-type-regular')
			<div class="desc not--truncated">
				<p class="desc-p" fr-truncate-lines>{!! $excerpt !!}</p>
			</div>
		@endif
		@if (in_array($post_type, ['news']) && !empty($publication_date))
			<p class="highlight-text">@if(!$hide_date){!! $publication_date !!}@endif</p>
		@endif
		@if (!empty($author) && is_string($author))
			<p class="highlight-text author" fr-truncate-lines>{!! $author !!}</p>
		@endif
		@if (!empty($formatted_date))
			<p class="date">{!! $formatted_date !!}</p>
		@endif
		@if ($post_type == 'event' && $formatted_time && $style !== 'card-type-regular')
			<div class="desc">
				<p>{{ $formatted_time }}</p>
			</div>
		@endif
		@if (!empty($format))
			<p class="highlight-text">{!! $format['name'] !!}</p>
		@endif
		@if ($post_type == 'event' && $excerpt && $style == 'card-type-regular')
			<div class="desc not--truncated">
				<p class="desc-p" fr-truncate-lines>{!! $excerpt !!}</p>
			</div>
		@endif
		@if ($post_type == 'event')
			<div class="sep"></div>
		@endif
		<div class="program-tags-container">{!! $program_tags !!}</div>
		@if (!empty($permalink))
			<x-cta-button :label="$permalink['label']" type="external_url" :external-url="$permalink['url']" :new-tab="$permalink['target']" style="secondary" />
		@endif
	</div>
</div>
