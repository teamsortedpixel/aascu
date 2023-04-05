<?php
	return [
		'news' => [
			'enter_title_here' => 'Enter News Article title',
			'menu_icon' => 'dashicons-megaphone',
			'supports' => ['title', 'editor', 'author', 'revisions', 'thumbnail', 'excerpt'],
			'show_in_rest' => true,
			'has_archive' => false,
			'labels' => [
				'singular' => 'News Article',
				'plural' => 'News Articles',
				'slug' => 'news',
			]
		],
	];