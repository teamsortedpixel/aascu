<?php
	return [
		'role' => [
			'hierarchical' => false, 
			'links' => ['resource', 'event', 'page'],
			'meta_box' => 'simple',
			'show_in_rest' => true,
			'allow_hierarchy' => false,
			'labels' => [
				'singular' => 'Role',
				'plural' => 'Roles',
			],
		],
	];