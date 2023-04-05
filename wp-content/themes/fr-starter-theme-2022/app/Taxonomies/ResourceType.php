<?php
	return [
		'resource-type' => [
			'hierarchical' => false, 
			'links' => ['resource'],
			'meta_box' => 'radio',
			'required' => true,
			'show_in_rest' => true,
			'allow_hierarchy' => false,
			'labels' => [
				'singular' => 'Resource Type',
				'plural' => 'Resource Types',
			],
		],
	];