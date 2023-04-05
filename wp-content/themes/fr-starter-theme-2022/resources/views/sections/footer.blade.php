{{-- 	<h2>Regular</h2>
	<x-card post="50" />
	<x-card post="146" />
	<x-card post="140" />
	<x-card post="149" /> 
	<h2>Condensed</h2>
	<x-card post="50" style="condensed" />
	<x-card post="146" style="condensed" />
	<x-card post="140" style="condensed" />
	<x-card post="149" style="condensed" />
	<h2>Condensed Small</h2>
	<x-card post="149" style="condensed-small" /> --}}
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col col-lg-12 top-row">
				<div class="row">
					<div class="col col-lg-3 dark-blue-container">
						<div class="footer-site-info wysiwyg-content">
							@if ($logo && is_array($logo))
								<img class="footer-logo" src="{{ $logo['url'] }}" alt="{{ $logo['alt'] }}" loading="lazy" />
							@else
								<p class="footer-tagline">{{ $site_name }}</p>
							@endif
						</div>
					</div>
					<div class="col col-lg-8 col-md-12 offset-md-0 dark-blue-container footer-links-cta-container">
						<div class="footer-content-wrapper">
							@if ($footer_cta_heading || $cta || $social_links)
								<div class="cta-heading-wrapper {{ $social_links ? 'has-social-links' : '' }}">					
									@if ($footer_cta_heading)
										<div class="wysiwyg-content">
											<p class="footer-cta-heading">{!! $footer_cta_heading !!}</p>
											@if ($cta)
												<x-cta-button :label="$cta['title']" style="primary" type="external_url" :external-url="$cta['url']" :new-tab="$cta['target']" />
											@endif
										</div>
									@endif
								</div>
								@if ($social_links)
									<x-social-links :data="$social_links" />
								@endif
							@endif
							@if (has_nav_menu('footer_navigation'))
								{!! wp_nav_menu($footer_navigation) !!}
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="col col-lg-12 bottom-row">
				<div class="row dark-blue-container fr-row-md-col">
					<div class="col col-lg-6 col-md-12 wysiwyg-content">
						<p class="copy-r">&copy; {{ $copyright_text }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<div id="app-sizer"></div>
