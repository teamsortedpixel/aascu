<div class="fr-block module filterable-content-module {{ $show_filters_in_frontend ? 'with-filters' : 'without-filters'  }} {{ $block->classes }}">
	<div class="container-fluid filters-container content-wrapper " ajax-config="{{ $ajax_config }}" @if (isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
        <div class="filters-result-container ajax-container">
            @if($show_filters_in_frontend)
                @include('components.taxonomy-filter.header-filters')
            @endif
            <div class="result-content ajax-content">
                <x-spinner />
                <div class="cards-container {{$post_type}}-cards" id="cards-container">
                    @if($block->preview && !empty($cards_data) && !empty($cards_data['cards']))
                        @foreach($cards_data['cards'] as $card)
                            {!! $card !!}
                        @endforeach
                    @endif
                </div>
                <div class="load-btn-container wysiwyg-content">
					<x-cta-button label="{{ empty($load_more_text) ? 'See more.' : $load_more_text }}" type="external_url" external-url="javascript:void(0)" :arrow="false" style="primary"/>
				</div>
				<div class="no-results-found-container wysiwyg-content">
					<h4>No results found.</h4>
					<p>Please update your search filters and try again.</p>
				</div>
				<div class="ajax-running-container wysiwyg-content">
					<p><i>loading...</i></p>
				</div>
            </div>
        </div>
        @if($show_filters_in_frontend)
        <div class="filters-input-container main-right-content">
            @if($show_filters_in_frontend)
                @include('components.taxonomy-filter.header-filters')
            @endif
            <div class="filters-input-container-inner main-sidebar">
                <x-search-bar />
                <form action="#" method="get" class="filters-form">
                    <div class="clear-filters-container">
                        <a href="javascript:void(0);" class="clear-filters-btn">Clear filters</a>
                    </div>
                    @include('components.taxonomy-filter.filters', ['filters' => $filter_taxonomies])
                    @include('components.taxonomy-filter.filters-dropdown', ['filters' => $filter_taxonomies])
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
