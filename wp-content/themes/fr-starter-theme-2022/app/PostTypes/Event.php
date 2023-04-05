<?php
	return [
		'event' => [
			'enter_title_here' => 'Enter Event title',
			'menu_icon' => 'dashicons-calendar',
			'supports' => ['title', 'editor', 'author', 'revisions', 'thumbnail', 'excerpt'],
			'show_in_rest' => true,
			'has_archive' => false,
			'labels' => [
				'singular' => 'Event',
				'plural' => 'Events',
			]
		],
	];