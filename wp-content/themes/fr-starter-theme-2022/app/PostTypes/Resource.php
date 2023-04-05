<?php
	return [
		'resource' => [
			'enter_title_here' => 'Enter Resource title',
			'menu_icon' => 'dashicons-welcome-write-blog',
			'supports' => ['title', 'editor', 'author', 'revisions', 'thumbnail', 'excerpt'],
			'show_in_rest' => true,
			'has_archive' => false,
			'labels' => [
				'singular' => 'Resource',
				'plural' => 'Resources',
			]
		],
	];