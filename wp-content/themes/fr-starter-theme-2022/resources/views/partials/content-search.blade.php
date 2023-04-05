@extends('layouts.app')

@section('content')
	<div class="container search-main-container">
		@include('partials.page-header')
		<x-search-bar />

		@if (! have_posts())
			<x-alert type="warning">
				{!! __('Sorry, no results were found.', 'sage') !!}
			</x-alert>
		@else
			<div class="search-results-container">
				@while(have_posts()) @php(the_post())
					@include('partials.content-search')
				@endwhile
			</div>
		@endif


		{!! get_the_posts_navigation(['class' => 'posts-nav-container']) !!}
	</div>
@endsection
