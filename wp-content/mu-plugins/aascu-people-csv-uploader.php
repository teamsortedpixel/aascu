<?php
/*
Plugin Name: AASCU People CSV Uploader
Description: CSV Interface to populate people database
Author: Dani Alvarez
Version: 0.0.1
*/

/**
* Create Admin Menu & Page
*/
add_action('admin_menu', 'aascu_pcu_plugin_setup_menu');
function aascu_pcu_plugin_setup_menu(){
	add_submenu_page(
		'edit.php?post_type=people',
		'People CSV Uploader',
		'CSV Uploader',
		'manage_options',
		'people-csv-uploader',
		'aascu_pcu_init'
	);
}

/**
* Show Content on the admin Screen
*/
function aascu_pcu_init(){
	echo "<h1>People CSV Uploader</h1>";
	echo '<div class="postbox acf-postbox">';
		echo '<form id="aascu-people-csv-uploader-form" style="padding:25px;">
		<h2 style="margin-top:0;">Select CSV File</h2>
		<div class="aascu-people-csv-label">
			<input type="file" id="aascuCsvFile" accept=".csv" />
		</div>
		<div class="aascu-people-csv-label">
			<label>
				<input type="checkbox" id="aascuWipePeopleDatabase" />
				Replace all existing content with new upload?
			</label>
		</div>
		<div class="aascu-people-csv-label">
			<input class="button button-primary button-large aascu-csv-submit-btn" disabled="disabled" type="submit" value="Upload" />
		</div>
		<div class="aascu-people-csv-label progress-container">
			<h4>Progress:</h4>
			<div class="aascu-csv-uploader-msg-box">
				<i>Process not started.</i>
			</div>
		</div>
		<div class="aascu-people-csv-label warning-container empty">
			<h4>Warnings:</h4>
			<div class="aascu-csv-uploader-msg-box"></div>
		</div>
		<div class="aascu-people-csv-label error-container empty">
			<h4>Errors:</h4>
			<div class="aascu-csv-uploader-msg-box"></div>
		</div>
		</form>
		<div class="aascu-people-csv-label" style="padding:0px 15px;">
		<p><i><b>Note:</b> Download the example CSV <a href="'.get_template_directory_uri().'/resources/example-csv/people-example-csv.csv" download>here.</a></i></p>
		</div>';
	echo '</div>';
}


/**
 * AJAX BACKEND CONFIG
 */
add_action('admin_init', function(){
	add_action('wp_ajax_fr_aascu_csv_upload', 'fr_aascu_csv_upload__ajax');
});

//Remove all people posts through SQL
function fr_aascu_remove_all_people_posts(){
	global $wpdb;

	$sql = "DELETE a,b,c
		FROM wp_posts a
		LEFT JOIN wp_term_relationships b
			ON (a.ID = b.object_id)
		LEFT JOIN wp_postmeta c
			ON (a.ID = c.post_id)
		WHERE a.post_type = '%s';";

	return $wpdb->query($wpdb->prepare($sql, 'people'));
}

function fr_aascu_csv_upload__ajax(){
	$args = [
		'row_data' => filter_input(INPUT_POST, 'row_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)?: [],
		'wipe_people_database' => filter_input(INPUT_POST, 'wipe_people_database')?: false,
	];
	
	$result = fr_aascu_csv_upload($args);

	if(is_wp_error($result)){
		wp_send_json_error($result);
	}else{
		wp_send_json_success($result);
	}
}

function fr_aascu_csv_upload($args){
	$result = [
		'status' => ''
	];

	//First check if we need to wipe database
	if(isset($args['wipe_people_database']) && $args['wipe_people_database']){
		$removed_result = fr_aascu_remove_all_people_posts();
		error_log('wipe_people_database result = ' . json_encode($removed_result));

		$result['status'] = 'People database emptied before new content upload. ';
	}

	if(isset($args['row_data']['First Name']) && strlen($args['row_data']['First Name']) && 
	isset($args['row_data']['Last Name']) && strlen($args['row_data']['Last Name']) &&
	isset($args['row_data']['Person ID']) && strlen($args['row_data']['Person ID'])){
		$person_id = trim($args['row_data']['Person ID']);

		$existing_person = fr_aascu_get_person_by_person_id($person_id);

		$result['status'] .= $args['row_data']['First Name'] .' '. $args['row_data']['Last Name']. ' bio successfully ';
	
		if($existing_person){
			//update person
			fr_aascu_update_person($existing_person->ID, $args['row_data']);
			$result['status'] .= 'updated!. ';
		}else{
			//add person post
			$new_post_id = fr_aascu_insert_person($args['row_data']);

			if($new_post_id){
				fr_aascu_update_person($new_post_id, $args['row_data']);
			}

			$result['status'] .= 'uploaded!. ';
		}

		$result['status'] .= '<span style="color:lightgray;font-style:italic;">(Post ID = ' . ($existing_person ? $existing_person->ID : $new_post_id) .')</span>';
	}else{
		$result['status'] .= 'Data row skipped, missing required column(s). ';
	}

	return $result;
}


function fr_aascu_get_person_by_person_id($person_id){
	$result = false;

	$query_args = [
		'post_type' => 'people',
		'posts_per_page' => -1,
		'offset' => 0,
		'post_status' => ['publish'],
		'meta_query' => [
			[
				'key' => 'person_id',
				'value' => trim($person_id),
				'compare' => '='
			]
		]
	];

	//error_log('$query_args');
	//error_log(wp_json_encode($query_args));

	$query = new WP_Query($query_args);
	wp_reset_postdata();

	return $query->posts ? $query->posts[0] : $result;
}

function fr_aascu_update_person($post_id, $data){
	$full_name = $data['First Name'] . ' ' . $data['Last Name'];

	//update ACF fields
	if(function_exists('acf')){
		$insert_arr = [
			['post_id', 'meta_key', 'meta_value'],
			[$post_id, 'person_id', $data['Person ID']],
			[$post_id, '_person_id', 'field_people_post_fields_person_id'],
			[$post_id, 'firstname', $data['First Name']],
			[$post_id, '_firstname', 'field_people_post_fields_firstname'],
			[$post_id, 'lastname', $data['Last Name']],
			[$post_id, '_lastname', 'field_people_post_fields_lastname'],
		];

		if(isset($data['Title']) && strlen($data['Title'])){
			array_push($insert_arr, [$post_id, 'title', $data['Title']]);
			array_push($insert_arr, [$post_id, '_title', 'field_people_post_fields_title']);
		}

		if(isset($data['Organization']) && strlen($data['Organization'])){
			array_push($insert_arr, [$post_id, 'description', $data['Organization']]);
			array_push($insert_arr, [$post_id, '_description', 'field_people_post_fields_description']);
		}

		if(isset($data['Phone']) && strlen($data['Phone'])){
			$phone_data = serialize([
				'number' => $data['Phone'],
				'country' => aascu_fr_get_country_code_from_phone_number($data['Phone'])
			]);

			array_push($insert_arr, [$post_id, 'phone', $phone_data]);
			array_push($insert_arr, [$post_id, '_phone', 'field_people_post_fields_phone']);
		}

		if(isset($data['Email']) && strlen($data['Email'])){
			array_push($insert_arr, [$post_id, 'email', $data['Email']]);
			array_push($insert_arr, [$post_id, '_email', 'field_people_post_fields_email']);
		}
		
		if(isset($data['Bio']) && strlen($data['Bio'])){
			array_push($insert_arr, [$post_id, 'bio', $data['Bio']]);
			array_push($insert_arr, [$post_id, '_bio', 'field_people_post_fields_bio']);
		}

		//profile pic
		if(isset($data['Profile Photo']) && strlen($data['Profile Photo'])){
			$profile_image_attachment_id = aascu_fr_get_attachment_id_by_filename($data['Profile Photo']);
			if($profile_image_attachment_id){
				array_push($insert_arr, [$post_id, 'profile_photo', $profile_image_attachment_id]);
				array_push($insert_arr, [$post_id, '_profile_photo', 'field_people_post_fields_profile_photo']);
			}
		}

		//social links
		$social_count = 0;
		if(isset($data['Linkedin URL']) && strlen($data['Linkedin URL'])){
			$social_count++;
			array_push($insert_arr, [$post_id, 'social_links_'.($social_count - 1).'_type', 'ln']);
			array_push($insert_arr, [$post_id, '_social_links_'.($social_count - 1).'_type', 'field_people_post_fields_social_links_type']);
			array_push($insert_arr, [$post_id, 'social_links_'.($social_count - 1).'_url', $data['Linkedin URL']]);
			array_push($insert_arr, [$post_id, '_social_links_'.($social_count - 1).'_url', 'field_people_post_fields_social_links_url']);
		}
		if(isset($data['Twitter URL']) && strlen($data['Twitter URL'])){
			$social_count++;
			array_push($insert_arr, [$post_id, 'social_links_'.($social_count - 1).'_type', 'tw']);
			array_push($insert_arr, [$post_id, '_social_links_'.($social_count - 1).'_type', 'field_people_post_fields_social_links_type']);
			array_push($insert_arr, [$post_id, 'social_links_'.($social_count - 1).'_url', $data['Twitter URL']]);
			array_push($insert_arr, [$post_id, '_social_links_'.($social_count - 1).'_url', 'field_people_post_fields_social_links_url']);
		}
		if($social_count > 0){
			array_push($insert_arr, [$post_id, 'social_links', $social_count]);
			array_push($insert_arr, [$post_id, '_social_links', 'field_people_post_fields_social_links']); 
		}

		//Update Fields
		fr_aascu_acf_bulk_insert($insert_arr);
	}

	//Update Tax
	//Departments
	if(isset($data['Groups']) && strlen($data['Groups'])){
		aascu_fr_update_departments($post_id, $data['Groups']);
	}

	//update title & update slug
	$post_update = [
		'ID' => $post_id,
		'post_title' => $full_name,
		'post_name' => sanitize_title_with_dashes($full_name)
	];

	wp_update_post( $post_update );
}

function fr_aascu_insert_person($data){
	$full_name = $data['First Name'] . ' ' . $data['Last Name'];

	return wp_insert_post(
		[
			'comment_status' =>	'closed',
			'ping_status' =>	'closed',
			'post_author' =>	1,
			'post_name' =>  sanitize_title_with_dashes($full_name),
			'post_title' =>	$full_name,
			'post_status' => 'publish',
			'post_type' =>	'people'
		]
	);
}

function fr_aascu_acf_bulk_insert($rows) {
	//if bad params, halt and return
	if(!$rows || count($rows) == 0) return;

	global $wpdb;
	// Extract column list from first row of data
	$columns = $rows[0];
	$columnList = '`' . implode('`, `', $columns) . '`';

	// Start building SQL, initialise data and placeholder arrays
	$sql = "INSERT INTO wp_postmeta ($columnList) VALUES\n";
	$placeholders = array();
	$data = array();

	// Build placeholders for each row, and add values to data array
	foreach ($rows as $i => $row) {
		if($i > 0){
			ksort($row);
			$rowPlaceholders = array();

			foreach ($row as $key => $value) {
				$data[] = $value;
				$rowPlaceholders[] = is_float($value) ? '%f' : (is_numeric($value) ? '%d' : '%s');
			}

			$placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
		}
	}

	// Stitch all rows together
	$sql .= implode(",\n", $placeholders);

	// Run the query.  Returns number of affected rows.
	return $wpdb->query($wpdb->prepare($sql, $data));
}


function aascu_fr_get_attachment_id_by_filename($filepath){
    $file = basename($filepath);
    $query_args = array(
        'post_status' => 'any',
        'post_type'   => 'attachment',
        'fields'      => 'ids',
        'meta_query'  => array(
            array(
                'value'   => $file,
                'compare' => 'LIKE',
            ),
        )
    );

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        return $query->posts[0]; //assume the first is correct; or process further if you need
    }
    return false;
}

function aascu_fr_update_departments($post_id, $deparments){
	$deparments = $deparments && strlen($deparments) ? array_map('trim', explode(',', $deparments)) : [];

	if($deparments && count($deparments)){
		$terms = aascu_fr_add_terms_to_taxonomy('department', $deparments);
		if(is_array($terms)){
			$to_insert = [];
			foreach ($terms as $t) {
				$to_insert[] = (int) $t['term_id'];
			}
			wp_set_post_terms($post_id, $to_insert, 'department');
		}
	}
}

function aascu_fr_add_terms_to_taxonomy($tax_name, $terms = []){
	$result = [];
	foreach ($terms as $t) {
		$exists = term_exists( $t, $tax_name );
		$result[] = $exists == 0 || $exists == null ? wp_insert_term(  $t, $tax_name ) : $exists;
	}
	return $result;
}

function aascu_fr_get_country_code_from_phone_number($phone){
	$result = 'US';
	
	$area_code = explode(' ', str_replace('+', '', $phone), 1);

	$phoneCodes = ['AF'=>'93','AL'=>'355','DZ'=>'213','AS'=>'1-684','AD'=>'376','AO'=>'244','AI'=>'1-264','AQ'=>'672','AG'=>'1-268','AR'=>'54','AM'=>'374','AW'=>'297','AU'=>'61','AT'=>'43','AZ'=>'994','BS'=>'1-242','BH'=>'973','BD'=>'880','BB'=>'1-246','BY'=>'375','BE'=>'32','BZ'=>'501','BJ'=>'229','BM'=>'1-441','BT'=>'975','BO'=>'591','BA'=>'387','BW'=>'267','BR'=>'55','IO'=>'246','VG'=>'1-284','BN'=>'673','BG'=>'359','BF'=>'226','BI'=>'257','KH'=>'855','CM'=>'237','CA'=>'1','CV'=>'238','KY'=>'1-345','CF'=>'236','TD'=>'235','CL'=>'56','CN'=>'86','CX'=>'61','CC'=>'61','CO'=>'57','KM'=>'269','CK'=>'682','CR'=>'506','HR'=>'385','CU'=>'53','CW'=>'599','CY'=>'357','CZ'=>'420','CD'=>'243','DK'=>'45','DJ'=>'253','DM'=>'1-767','DO'=>'1-809','TL'=>'670','EC'=>'593','EG'=>'20','SV'=>'503','GQ'=>'240','ER'=>'291','EE'=>'372','ET'=>'251','FK'=>'500','FO'=>'298','FJ'=>'679','FI'=>'358','FR'=>'33','PF'=>'689','GA'=>'241','GM'=>'220','GE'=>'995','DE'=>'49','GH'=>'233','GI'=>'350','GR'=>'30','GL'=>'299','GD'=>'1-473','GU'=>'1-671','GT'=>'502','GG'=>'44-1481',
'GN'=>'224','GW'=>'245','GY'=>'592','HT'=>'509','HN'=>'504','HK'=>'852','HU'=>'36','IS'=>'354','IN'=>'91','ID'=>'62','IR'=>'98','IQ'=>'964','IE'=>'353','IM'=>'44-1624','IL'=>'972','IT'=>'39','CI'=>'225','JM'=>'1-876','JP'=>'81','JE'=>'44-1534','JO'=>'962','KZ'=>'7','KE'=>'254','KI'=>'686','XK'=>'383','KW'=>'965','KG'=>'996','LA'=>'856','LV'=>'371','LB'=>'961','LS'=>'266','LR'=>'231','LY'=>'218','LI'=>'423','LT'=>'370','LU'=>'352','MO'=>'853','MK'=>'389','MG'=>'261','MW'=>'265','MY'=>'60','MV'=>'960','ML'=>'223','MT'=>'356','MH'=>'692','MR'=>'222','MU'=>'230','YT'=>'262','MX'=>'52','FM'=>'691','MD'=>'373','MC'=>'377','MN'=>'976','ME'=>'382','MS'=>'1-664','MA'=>'212','MZ'=>'258','MM'=>'95','NA'=>'264','NR'=>'674','NP'=>'977','NL'=>'31','AN'=>'599','NC'=>'687','NZ'=>'64','NI'=>'505','NE'=>'227','NG'=>'234','NU'=>'683','KP'=>'850','MP'=>'1-670','NO'=>'47','OM'=>'968','PK'=>'92','PW'=>'680','PS'=>'970','PA'=>'507','PG'=>'675','PY'=>'595','PE'=>'51','PH'=>'63','PN'=>'64','PL'=>'48','PT'=>'351','PR'=>'1-787','QA'=>'974','CG'=>'242','RE'=>'262',
'RO'=>'40','RU'=>'7','RW'=>'250','BL'=>'590','SH'=>'290','KN'=>'1-869','LC'=>'1-758','MF'=>'590','PM'=>'508','VC'=>'1-784',
'WS'=>'685','SM'=>'378','ST'=>'239','SA'=>'966','SN'=>'221','RS'=>'381','SC'=>'248','SL'=>'232','SG'=>'65','SX'=>'1-721','SK'=>'421','SI'=>'386','SB'=>'677','SO'=>'252','ZA'=>'27','KR'=>'82','SS'=>'211','ES'=>'34','LK'=>'94','SD'=>'249','SR'=>'597','SJ'=>'47','SZ'=>'268','SE'=>'46','CH'=>'41','SY'=>'963','TW'=>'886','TJ'=>'992','TZ'=>'255','TH'=>'66','TG'=>'228','TK'=>'690','TO'=>'676','TT'=>'1-868','TN'=>'216','TR'=>'90','TM'=>'993','TC'=>'1-649','TV'=>'688','VI'=>'1-340','UG'=>'256','UA'=>'380','AE'=>'971','GB'=>'44','US'=>'1','UY'=>'598','UZ'=>'998','VU'=>'678','VA'=>'379','VE'=>'58','VN'=>'84','WF'=>'681','EH'=>'212','YE'=>'967','ZM'=>'260','ZW'=>'263'];

	$found = array_search($area_code, $phoneCodes);

	if($found !== false){
		$result = $found;
	}

	return strtolower($result);
}

/**
* Load Styles and Scripts
*
* @desc Load custom CSS and JS files on specific pages of your plugin based on the page slug.
*/
add_action('admin_enqueue_scripts', function () {
	$current_screen = get_current_screen();
	if ( strpos($current_screen->base, 'people-csv-uploader') === false) {
		return;
	} else {
		wp_enqueue_script('jquery-csv', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.21/jquery.csv.min.js', ['jquery'], false, true);
	?>
	<style>
		/*  */
		.aascu-people-csv-label{
			padding:5px 0;
			display: block;
		}
		.aascu-csv-submit-btn{
			margin-top: 10px !important;
		}
		.warning-container{
			color:#bf9600;
		}
		.error-container{
			color:#b32d2e;
		}
		.progress-container .aascu-csv-uploader-msg-box{
			max-height: 400px;
			overflow-y: auto;
		}
		.progress-container .aascu-csv-uploader-msg-box p {
			font-style: italic;
		}
		.error-container.empty,
		.warning-container.empty{
			display:none;
		}

	</style>
	<script>
		window.fr_aascu_csv_upload_ajax_config = {
			url: '<?= admin_url('admin-ajax.php') ?>',
			action: 'fr_aascu_csv_upload'
		};

		function deferJq(method) {
			if (window.jQuery) {
				initializeJs();
			} else {
				setTimeout(function() { deferJq(method) }, 50);
			}
		}

		function initializeJs(){
			jQuery(document).ready(function(){
				var $ = jQuery;
				var $csvInput = $('#aascuCsvFile');
				var $submit = $('.aascu-csv-submit-btn');
				var $wipeDatabaseCheckbox = $('#aascuWipePeopleDatabase');
				var $progressContainer = $('.progress-container');
				var $warningContainer = $('.warning-container');
				var $errorContainer = $('.error-container');
				var dataObj = [];

				$csvInput.on('change', function(evt){
					var f = evt.target.files[0]; 

					//Reset containers
					resetTextContainers();
					$submit.attr('disabled', 'disabled');

					if(f){
						var reader = new FileReader();
						reader.readAsText(f);
						reader.onload = function(event) {
							var dataArray = $.csv.toArrays(event.target.result);
							dataObj = $.csv.toObjects(event.target.result);

							updateProgressBox(`<p>Analyzing file...</p>`);

							var validation = validateData(dataArray);
							
							if(validation.isValid){
								$submit.removeAttr('disabled');
								updateProgressBox(`<p>${validation.msg}</p>`, 'append');

								//check for warnings
								checkDataForWarnings(dataObj);

							}else{
								updateErrorBox(`<p>${validation.msg}</p>`);
								updateProgressBox(`<p><b>Errors found. Process stopped.</b></p>`, 'append');
							}
						};
					}
				});

				$submit.on('click', function(ev){
					ev.preventDefault();
					//on button click
					onButtonClicked(function(){
						//start upload process ajax

						updateProgressBox(`<p>Uploading data. <b>Please do not navigate away from this page until the upload is completed. Thank you!</b></p>`,'append');

						startUploadProcess(dataObj, function(){
							console.log('all done!');
						});
					});
				});

			});
		}

		function updateWarningBox(msg, method){
			var $ = jQuery;
			var $container = $('.warning-container');
			var $textContainer = $container.find('> .aascu-csv-uploader-msg-box');
			
			if(method && method == 'append'){
				$textContainer.append($(msg));
			}else{
				$textContainer.html('').append($(msg));
			}

			$container.toggleClass('empty', !$textContainer.html().length);
		}

		function updateErrorBox(msg, method){
			var $ = jQuery;
			var $container = $('.error-container');
			var $textContainer = $container.find('> .aascu-csv-uploader-msg-box');
			
			if(method && method == 'append'){
				$textContainer.append($(msg));
			}else{
				$textContainer.html('').append($(msg));
			}

			$container.toggleClass('empty', !$textContainer.html().length);
		}

		function updateProgressBox(msg, method){
			var $ = jQuery;
			var $container = $('.progress-container > .aascu-csv-uploader-msg-box');
			
			if(method && method == 'append'){
				$container.append($(msg));
			}else{
				$container.html('').append($(msg));
			}

			var objDiv = $container[0];
			objDiv.scrollTop = objDiv.scrollHeight;

		}
		
		function validateData(data){
			var response = {
				isValid: true,
				msg: `File is valid! Click the 'Upload' button to initialize the database update process.`
			};
			
			//1. Check if not empty
			if(!data || data.length == 0){
				return {
					isValid: false,
					msg: 'File Invalid! No rows of data have been found.'
				}
			}

			//2. Check if at least on line of data
			if(data.length < 2){
				return {
					isValid: false,
					msg: 'File Invalid! File must contain at least 2 lines of data (one row of headers and another of entry data).'
				}
			}
			
			//3. Check if columns are missing required column(s)
			var existingRequiredColumns = data[0].filter(function(n) {
    			return getRequiredColumnsArray().indexOf(n) !== -1;
			});

			if(!sameMembers(getRequiredColumnsArray(), existingRequiredColumns)){
				return {
					isValid: false,
					msg: 'File Invalid! There are missing required columns, please make sure the following columns exist: <b>' + getRequiredColumnsArray().join(", ") + '</b>.'
				}
			}
			
			return response;
		}

		function checkDataForWarnings(data){
			var requiredColumns = getRequiredColumnsArray();

			data.forEach(function(el, i){
				var missingReqCols = [];
				requiredColumns.forEach(function(rcol, j){
					if(!el[rcol] || el[rcol].length == 0){
						missingReqCols.push(rcol);
					}
				});

				if(missingReqCols.length){
					missingReqCols.forEach(function(rcol, j){
						updateWarningBox('<p><i>Row ('+(i + 1)+') is missing data for the following required columns: '+missingReqCols.join(", ")+'. Row will not be processed.</i></p>', 'append');
					});
				}
			});
		}

		function getRequiredColumnsArray(){
			return [
				'First Name',
				'Last Name',
				'Person ID'
			];
		}

		function containsAll(arr1, arr2) {
			return arr2.every(arr2Item => arr1.includes(arr2Item))
		} 
                
		function sameMembers(arr1, arr2){
			return containsAll(arr1, arr2) && containsAll(arr2, arr1);
		}

		function resetTextContainers() {
			updateWarningBox('');
			updateErrorBox('');
			updateProgressBox('<i>Process not started.</i>');
		}

		function onButtonClicked(callback){
			if (confirm('People database will be update. Are you sure you want to continue?')) {
				if(callback){
					callback();
				}
			}
		}

		function startUploadProcess(dataArr, onDoneCallback){
			var $ = jQuery;
			var limit = dataArr.length;
			var index = 0;

			//lock inputs
			$('#aascuCsvFile').attr('disabled', 'disabled');
			$('.aascu-csv-submit-btn').attr('disabled', 'disabled');
			$('#aascuWipePeopleDatabase').attr('disabled', 'disabled');

			processDataRow(index, dataArr, function(response){
				if(response.success){
					//ALL DONE!!!
					updateProgressBox('<p style="color:green;"><i><b>Success! People database updated correctly. You can leave the page now.</b></i></p>', 'append');

				}else{
					//return error;
					updateProgressBox('<p style="color:red;"><i>Error found. Message: '+response.msg+'</i></p>', 'append');
				}
			})
		}

		function processDataRow(index, dataArr, finishedCallback){
			var $ = jQuery;

			$.ajax({
				url: window.fr_aascu_csv_upload_ajax_config.url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: window.fr_aascu_csv_upload_ajax_config.action,
					wipe_people_database: index == 0 && $('#aascuWipePeopleDatabase').is(':checked') ? 1 : 0,
					row_data: dataArr[index]
				},
				/**
				 * A function to be called if the request succeeds.
				 */
				success: function(data) {
					index = index +1;       
					//  Recursive/next call if current call is finished OK and there are elements
					if(index < dataArr.length){
						updateProgressBox('<p><i>Row '+index+' processed. '+(data.data.status ? data.data.status :'')+'</i></p>', 'append');

						processDataRow(index, dataArr, finishedCallback);
					}else{
						updateProgressBox('<p><i>Row '+index+' processed. '+(data.data.status ? data.data.status :'')+'</i></p>', 'append');
						if(finishedCallback){
							finishedCallback({
								success: true
							});
						}
					}
				},
				/**
				 * A function to be called if the request fails. 
				 */
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('jqXHR:');
					console.log(jqXHR);
					console.log('textStatus:');
					console.log(textStatus);
					console.log('errorThrown:');
					console.log(errorThrown);

					if(finishedCallback){
						finishedCallback({success: false, msg: 'There was an error processing the request. Error: ' + errorThrown});
					}
					//  We don't do a recursive/next call because current call has failed
				},
			});
		}

		//on page load
		deferJq();
	</script>
<?php
	}
});