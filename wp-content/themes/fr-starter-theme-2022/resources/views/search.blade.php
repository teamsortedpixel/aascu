@extends('layouts.app')

@section('content')
	<div class="search-hero dark-blue-container">
		<div class="container">
			<div class="row">
				<div class="col col-lg-8 wysiwyg-content">
					<x-search-bar />
				</div>
			</div>
		</div>
	</div>
	<div class="container search-main-container">
		<div class="row">
			<div class="col search-results-container" fr-status="ajax-running" ajax-config='{{ $ajax_config }}'>
				<div class="card-grid-container"></div>
				<div class="load-btn-container">
					<x-cta-button label="Load More" type="external_url" external-url="javascript:void(0)" />
				</div>
				<div class="no-results-found-container wysiwyg-content">
					<h4>No results found.</h4>
					<p>Please update your search term and try again.</p>
				</div>
				<div class="ajax-running-container wysiwyg-content">
					<p><i>loading...</i></p>
				</div>
			</div>
		</div>
	</div>
@endsection
