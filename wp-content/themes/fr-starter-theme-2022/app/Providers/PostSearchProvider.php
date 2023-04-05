<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PostSearchProvider extends ServiceProvider
{

	const POST_TYPE = 'post';
	const GENERAL_SEARCH_ACTION = 'get_general_search';
	const GENERAL_SEARCH_POST_TYPES = ['post', 'page'];
	const POSTS_PER_PAGE = 9;

	//CUSTOM FIELD FOR SORTING ELEMENTS BETWEEN DIFFERENT POST TYPES
	const DATE_SORT_FIELD = 'custom_date_sort';

	public function register(){
		add_action('save_post', function ($post_id, $post){
			if(in_array($post->post_type, ['event', 'news', 'resource', 'page']) && !wp_is_post_autosave($post) && $post->post_status !== 'auto-draft' && !wp_is_post_revision($post_id)){
				self::SetDateSortField($post_id);
			}
		}, 10, 2);
	}

	public static function GetAjaxAction($post_type = ''){
		$result = 'get_posts';

		switch ($post_type) {
			case 'project':
				$result = 'get_projects';
				break;
			default:
				$result = self::GENERAL_SEARCH_ACTION;
				break;
		}

		return $result;
	}

	public static function SetAjaxActions(){
		//PROJECTS
		//wp_die(var_dump(self::GetAjaxAction('general')));
		add_action('wp_ajax_' . self::GetAjaxAction('general'), function(){
			return self::GetPostsAjax('general');
		});

		add_action('wp_ajax_nopriv_' . self::GetAjaxAction('general'), function(){
			return self::GetPostsAjax('general');
		});
		//THE REST
		//TO DO
	}

	public static function GetPostsAjax($post_type = self::POST_TYPE){
		$args = [
			'post_type' => $post_type,
			'posts_per_page' => filter_input(INPUT_GET, 'posts_per_page')?: false,
			'page_number' => filter_input(INPUT_GET, 'page_number')?: false,
			'search' => filter_input(INPUT_GET, 'search')?: false,
			'region' => filter_input(INPUT_GET, 'region')?: false,
			'service' => filter_input(INPUT_GET, 'service')?: false,
			'year' => filter_input(INPUT_GET, 'year')?: false,
			'status' => filter_input(INPUT_GET, 'status')?: false,
		];

		$result = self::GetPosts($args);

		if(is_wp_error($result)){
			wp_send_json_error($result);
		}else{
			wp_send_json_success(self::ConvertDataToHtmlArray($result, $post_type == 'general' ? 'search-result-card' : $post_type));
		}
	}

	public static function GetPosts($args = []){
		$result = [];

		//default values
		$posts_per_page = isset($args['posts_per_page']) && $args['posts_per_page'] ? intval($args['posts_per_page']) : self::POSTS_PER_PAGE;
		$page = isset($args['page_number']) ? intval($args['page_number']) : 0;
		$post_type = isset($args['post_type']) ? $args['post_type'] : self::POST_TYPE;

		//FOR GENERAL SEARCH
		if($post_type == 'general'){
			$post_type = [
				'post',
				'page'
			];
		}

		$query_args = [
			'post_type' => $post_type == 'general' ? self::GENERAL_SEARCH_POST_TYPES : $post_type,
			'posts_per_page' => $posts_per_page,
			'offset' => $posts_per_page * $page,
			'post_status' => ['publish'],
			'tax_query' => [],
			'meta_query' => []
		];

		$taxonomies = array_filter([
			$post_type == 'general' ? 'program' : null,
		]);

		foreach($taxonomies as $t){
			$query_args['tax_query'] = isset($args[$t]) && $args[$t] ? array_merge($query_args['tax_query'], [
				[
					'taxonomy' => $t,
					'field' => 'slug',
					'terms' => $args[$t],
					'operator' => 'IN'
				]
			]) : $query_args['tax_query'];
		}

		$properties = array_filter([]);

		foreach ($properties as $p) {
			$query_args['meta_query'] = isset($args[$p]) && $args[$p] ? array_merge($query_args['meta_query'], [
				[
					'key' => $p,
					'value' => $args[$p],
					'compare' => '='
				]
			]) : $query_args['meta_query'];
		}


		if(isset($args['search']) && $args['search']){
			$query_args['s'] = $args['search'];
		}

		error_log('$query_args');
		error_log(wp_json_encode($query_args));

		$query = new \WP_Query($query_args);
		wp_reset_postdata();

		return $query->posts ?: $result;
	}

	public static function ConvertDataToHtmlArray($data_arr, $template_name = 'search-result-card'){
		$result = [];

		foreach ($data_arr?: [] as $i) {
			$card_data = \App\Providers\CardsDataProvider::get($i);

			$result[] = view('components.' . $template_name, [
				'post_type' => $card_data['post_type'],
				'permalink' => $card_data['permalink'],
				'title' => $card_data['title'],
				'excerpt' => $card_data['excerpt'],
				'program_tags' => $card_data['program_tags'],
				'image' => $card_data['image']
			])->render();
		}

		return $result;
	}

	public static function SetDateSortField($post_id){
		$post_type = get_post_type($post_id);
		$post_creation_date_unix = strtotime(get_the_date('', $post_id));
		$field_value = $post_creation_date_unix;

		switch ($post_type) {
			case 'event':
				$field_value = strtotime(get_field('start_date', $post_id));
				break;
			default:
				break;
		}

		update_post_meta($post_id, self::DATE_SORT_FIELD, $field_value);
	}

	public static function GetStateNameByAbbv($abbv){
		$result = '';

		$data = [
			"AL" => "Alabama",
			"AK" => "Alaska",
			"AS" => "American Samoa",
			"AZ" => "Arizona",
			"AR" => "Arkansas",
			"CA" => "California",
			"CO" => "Colorado",
			"CT" => "Connecticut",
			"DE" => "Delaware",
			"DC" => "District Of Columbia",
			"FM" => "Federated States Of Micronesia",
			"FL" => "Florida",
			"GA" => "Georgia",
			"GU" => "Guam",
			"HI" => "Hawaii",
			"ID" => "Idaho",
			"IL" => "Illinois",
			"IN" => "Indiana",
			"IA" => "Iowa",
			"KS" => "Kansas",
			"KY" => "Kentucky",
			"LA" => "Louisiana",
			"ME" => "Maine",
			"MH" => "Marshall Islands",
			"MD" => "Maryland",
			"MA" => "Massachusetts",
			"MI" => "Michigan",
			"MN" => "Minnesota",
			"MS" => "Mississippi",
			"MO" => "Missouri",
			"MT" => "Montana",
			"NE" => "Nebraska",
			"NV" => "Nevada",
			"NH" => "New Hampshire",
			"NJ" => "New Jersey",
			"NM" => "New Mexico",
			"NY" => "New York",
			"NC" => "North Carolina",
			"ND" => "North Dakota",
			"MP" => "Northern Mariana Islands",
			"OH" => "Ohio",
			"OK" => "Oklahoma",
			"OR" => "Oregon",
			"PW" => "Palau",
			"PA" => "Pennsylvania",
			"PR" => "Puerto Rico",
			"RI" => "Rhode Island",
			"SC" => "South Carolina",
			"SD" => "South Dakota",
			"TN" => "Tennessee",
			"TX" => "Texas",
			"UT" => "Utah",
			"VT" => "Vermont",
			"VI" => "Virgin Islands",
			"VA" => "Virginia",
			"WA" => "Washington",
			"WV" => "West Virginia",
			"WI" => "Wisconsin",
			"WY" => "Wyoming",
			//SPECIAL CASES FOR BAHAMAS, CANADA AND MEXICO
			"Bahamas" => "Bahamas", 
			"Canada" => "Canada",
			"CAN" => "Canada",
			"Mexico" => "Mexico",
			"MX" => "Mexico"
		];
		
		if(array_key_exists($abbv, $data)){
			$result = $data[$abbv];
		}

		return $result;
	}
}
