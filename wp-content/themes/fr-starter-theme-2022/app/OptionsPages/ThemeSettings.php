<?php
	namespace App;

	use StoutLogic\AcfBuilder\FieldsBuilder;

	if(function_exists('acf_add_options_page')){
		acf_add_options_page([
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug' => 'theme-settings',
			'capability' => 'administrator',
			'redirect' => false
		]);
	}