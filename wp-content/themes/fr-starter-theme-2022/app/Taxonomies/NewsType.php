<?php
	return [
		'news-type' => [
			'hierarchical' => false, 
			'links' => ['news'],
			'meta_box' => 'radio',
			'required' => true,
			'show_in_rest' => true,
			'allow_hierarchy' => false,
			'labels' => [
				'singular' => 'News Type',
				'plural' => 'News Types',
			],
		],
	];