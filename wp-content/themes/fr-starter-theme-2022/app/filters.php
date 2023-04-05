<?php
	/**
	 * Theme filters.
	 */
	namespace App;

	/**
	 * Add "… Continued" to the excerpt.
	 *
	 * @return string
	 */
	add_filter('excerpt_more', function () {
		return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
	});

	add_filter( 'wpseo_metabox_prio', function() {
		return 'low';
	});

	/**
	 * For Theme Settings > Programs Submenu, let's filter out all pages that don't have a program taxonomy
	 * term associated to it.
	 */
	add_filter('acf/fields/post_object/query', function ($args, $field, $post_id) {

		if(isset($field['wrapper']['class']) && strpos($field['wrapper']['class'], "fr-filter-programs") === false) return $args;

		$args['tax_query'] = [
			[
				'taxonomy' => 'program',
				'operator' => 'EXISTS'
			]
		];

		return $args;
	}, 10, 3);

	//Custom ACF anchor field validation
	add_filter('acf/validate_value/type=text', function($valid, $value, $field, $input_name){

		//if not has class
		if(!$field['wrapper'] || !$field['wrapper']['class'] || $field['wrapper']['class'] !== 'fr-validate-anchor-field') return $valid;

		// Bail early if value is already invalid.
		if( $valid !== true ) {
			return $valid;
		}

		//IF has spaces
		if($value == trim($value) && strpos($value, ' ') !== false){
			return 'Can\'t have spaces!.';
		}

		//IF has special chars
		if(strpos($value, '#') !== false || strpos($value, '!') !== false || strpos($value, '@') !== false){
			return 'Can\'t have special chars (#, !, @) on the value!.';
		}

		return $valid;
	}, 10, 4);

	function getACFFieldValue($inputName){
		$fieldNameArray = explode('][', substr($inputName, 4, -1));
		$fieldValue = $_POST['acf'];
		foreach($fieldNameArray as $child){
			$fieldValue =$fieldValue[$child];
		}

		return $fieldValue;
	}

	add_filter('acf/validate_value/type=date_time_picker', function ($valid, $value, $field, $input) {
		//if not has class
		if(!$field['wrapper'] || !$field['wrapper']['class'] || $field['wrapper']['class'] !== 'fr-validate-end-date') return $valid;

		// Bail early if value is already invalid.
		if( $valid !== true ) {
			return $valid;
		}

		$start_date_field_name = str_replace('_end_date', '_start_date', $input);
		$end_date_field_name = $input;
		$start_value = getACFFieldValue($start_date_field_name);
		$start_value = new \DateTime($start_value);
		$end_value = getACFFieldValue($end_date_field_name);
		$end_value = new \DateTime($end_value);

		if ($end_value < $start_value) {
			$valid = 'The end date must come after the start date.';
		}

		return $valid;
	}, 10, 4);

	// Filter for validations of acf block editor fields
	add_action( 'acf/validate_save_post', function () {
		// bail early if no $_POST
		$acf = false;
		foreach($_POST as $key => $value) {
			if (strpos($key, 'acf') === 0) {
				if (! empty( $_POST[$key] ) ) {
					acf_validate_values( $_POST[$key], $key);
				}
			}
		}
	}, 5 );

	/**
	 * Change the default Gform input submit element to actual button
	 */
	add_filter('gform_submit_button', function ( $button, $form ) {
		return "<button class='cta-button primary with-arrow gform_submit' id='gform_submit_button_{$form['id']}'>Submit<b></b></button>";
	}, 10, 2 );

	/**
	 * Changing default error message Gravity Forms (GF2.5)
	 */
	add_filter("gform_validation_message", function ($message, $form){
		return '<h2 class="gform_submission_error hide_summary">
					There was a problem with your submission. Please review the highlighted fields above.
				</h2>';
	}, 10, 2);

	add_action('admin_head', function(){
		$taxonomies = get_taxonomies(array( '_builtin' => FALSE ), 'objects');

		$params = [
			'required_taxonomies' => []
		];

		foreach ($taxonomies?: [] as $tax) {
			if(isset($tax->required) && $tax->required){
				$params['required_taxonomies'][] = [
					'name' => $tax->name,
					'links' => isset($tax->links) && is_array($tax->links)? $tax->links : []
				];
			}
		}

		echo
			'<script>
				window.fr_custom_validation = JSON.parse(\''.json_encode($params, JSON_HEX_APOS).'\');
			</script>';
	}, 10, 2);

	// enables the “Add Form” button for the page and post editor when the ACF plugin is active.
	add_filter( 'gform_display_add_form_button', function ( $display_add_form_button ) {
		if ( $display_add_form_button || ! function_exists( 'acf' ) ) {
			return $display_add_form_button;
		}

		return true;
	} );


	/*
	* Modify TinyMCE editor to remove H6 and Preformatted.
	*/
	add_filter('tiny_mce_before_init', function($init) {
		// Add block format elements you want to show in dropdown
		$init['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
		return $init;
	} );

	/**
	 * Filter for showing the download file for example CSV on Network Map
	 *
	 * @param [type] $field
	 * @return void
	 */
	// Apply to all fields.
	add_action('acf/render_field', function ( $field ) {
		if(isset($field['wrapper']['class']) && $field['wrapper']['class'] == 'fr-network-map-file-field'){
			$file_uri = get_template_directory_uri() . '/resources/example-csv/network-map-example.csv';
			echo '<p><i>Download the example CSV <a href="'.$file_uri.'" download>here.</a></i></p>';
		}
	});
