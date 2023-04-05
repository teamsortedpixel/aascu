<a href="{{ !empty($permalink) && !$has_empty_bio?$permalink['url']:'javascript:void(0);' }}" rel="People" class="fr-card people-card type-person {{ !empty($permalink) && !$has_empty_bio?'':'disabled' }}">
	@if($profile_photo)
		<x-responsive-acf-image :image="$profile_photo" size="thumbnail" class="profile-img" />
	@else
		<div class="empty profile-img"></div>
	@endif
	<div class="card-info-wrapper">
		<div class="wysiwyg-content">
			<h4 class="firstname">{!! $firstname !!}</h4>
			<h4 class="lastname">{!! $lastname !!}</h4>
			<p class="title" fr-truncate-lines="3" data-title="{!! htmlentities($title) !!}">{!! $title !!}</p>
			<p class="description" fr-truncate-lines="3" data-title="{!! htmlentities($description) !!}">{!! $description !!}</p>
		</div>
	</div>
</a>