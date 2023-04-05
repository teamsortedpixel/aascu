<button class="search-btn {{ isset($extra_class) ? $extra_class : '' }} {{ isset($has_dropdown) && $has_dropdown ? 'has-search-dropdown' : '' }}" {!! isset($attr) ? $attr : '' !!}>
	@svg('images.search')
	@if (isset($has_dropdown) && $has_dropdown)
		@svg('images.nav-close')
	@endif
</button>