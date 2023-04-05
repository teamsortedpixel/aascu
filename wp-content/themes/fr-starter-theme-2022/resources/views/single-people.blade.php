@extends('layouts.app')

@section('content')
	@while(have_posts()) @php(the_post())
		<div class="fr-page-builder-container">
			@include('blocks.hero', ['content' => $hero_content, 'circles' => $circles, 'block' => $extra_settings])
			<div class="fr-content-row section-bg-white vert-pad-default vert-stack-middle bio-row">
				<div class="container">
					@if ($bio && strlen($bio))
						@include('blocks.fr-page-builder-module-wysiwyg', ['content' => $bio, 'block' => $bio_block_settings])
					@endif
				</div>
			</div>
		</div>
	@endwhile
@endsection
