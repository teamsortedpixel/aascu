<header class="banner fr-header">
	@include('sections.upcoming-opportunities')
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<a class="brand" href="{{ home_url('/') }}">
				@if ($logo && is_array($logo))
					<img class="header-logo" src="{{ $logo['url'] }}" alt="{{ $logo['alt'] }}" loading="lazy">
				@else
					{!! $siteName !!}
				@endif
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerMenuContent" aria-controls="headerMenuContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon">
					@svg('images.nav-hamb')
					@svg('images.nav-close')
				</span>
			</button>
			<div class="collapse navbar-collapse" id="headerMenuContent">
				<div class="hide-on-desktop">
					<ul class="secondary-nav-items-ul">
						@foreach ($programs_submenu as $item)
							<li>
								<x-secondary-nav-dropdown-item :color="$item['accent_color']" :text-color="$item['text_color']" :title="$item['title']" :description="$item['description']" :permalink="$item['permalink']" />
							</li>
						@endforeach
					</ul>
				</div>
				@if (has_nav_menu('primary_navigation'))
					{!! wp_nav_menu($primaryNavigation) !!}
				@endif
				<div class="hide-on-desktop">
					<x-search-bar />
				</div>
			</div>
			<div class="general-search-container hide-on-mobile">
				@include('partials.search-button', ['extra_class' => 'general-search-btn', 'has_dropdown' => true])
				<div class="search-dropdown">
					<div class="search-dropdown-inner">
						<form action="{{ home_url('/') }}">
							<input type="text" name="s" placeholder="Search"/>
							<b>
								<input type="submit">
								@svg('images.arrow')
							</b>
						</form>
					</div>
				</div>
			</div>
		</div>
	</nav>
	<div class="navbar secondary-navbar-lg hide-on-mobile">
		<div class="container">
			<ul class="secondary-nav-items-ul">
				@foreach ($programs_submenu as $item)
					<li>
						<x-secondary-nav-dropdown-item :color="$item['accent_color']" :text-color="$item['text_color']" :title="$item['title']" :description="$item['description']" :permalink="$item['permalink']" />
					</li>
				@endforeach
			</ul>
		</div>
	</div>
</header>

@php
	if(isset($_GET['test_api']) && $_GET['test_api'] = '123'){
		$test = \App\Providers\ApiProvider::getMapData();
		wp_die(var_dump($test));
	}
@endphp