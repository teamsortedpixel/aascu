<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\PostSearchProvider;

class FilterableContentProvider extends ServiceProvider
{

	const POST_TYPE = 'event';
	const AJAX_ACTION = 'get_filterable_content';
	const POSTS_PER_PAGE = 9;
	const ORDER_BY = 'date';

	public static function SetAjaxActions(){
		add_action('wp_ajax_' . self::AJAX_ACTION , function(){
			return self::GetPostsAjax();
		});
		add_action('wp_ajax_nopriv_' . self::AJAX_ACTION , function(){
			return self::GetPostsAjax();
		});
	}

	public static function GetAjaxConfig($blockData)
    {
        //ajax config
        $ajax_config = [
			'post_type' => [$blockData['post_type']],
			'show_filters_in_frontend' => $blockData['show_filters_in_frontend'],
			'order_by' => $blockData['order_by'],
            'action' => self::AJAX_ACTION,
            'posts_per_page' => $blockData['posts_per_page'] ? :self::POSTS_PER_PAGE,
            'page' => 1
		];

		if($blockData['show_filters_in_frontend']){
			$ajax_config = array_merge($ajax_config, [
				'event-type_tax' => filter_input(INPUT_GET, 'event-type_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'event-type_tax', FILTER_UNSAFE_RAW)): [],
				'news-type_tax' => filter_input(INPUT_GET, 'news-type_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'news-type_tax', FILTER_UNSAFE_RAW)): [],
				'resource-type_tax' => filter_input(INPUT_GET, 'resource-type_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'resource-type_tax', FILTER_UNSAFE_RAW)): [],
				'role_tax' => filter_input(INPUT_GET, 'role_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'role_tax', FILTER_UNSAFE_RAW)): [],
				'format_tax' => filter_input(INPUT_GET, 'format_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'format_tax', FILTER_UNSAFE_RAW)): [],
				'program_tax' => filter_input(INPUT_GET, 'program_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'program_tax', FILTER_UNSAFE_RAW)): [],
				'post_tag_tax' => filter_input(INPUT_GET, 'post_tag_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'post_tag_tax', FILTER_UNSAFE_RAW)): [],
			]);
		}
		else {
			$ajax_config = array_merge($ajax_config, [
				'news-type_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['news_types']??[]),
				'resource-type_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['resource_types']??[]),
				'role_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['roles']??[]),
				'format_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['event_formats']??[]),
				'program_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['programs']??[]),
				'post_tag_tax' => self::GetTermsSlugs($blockData['taxonomy_filters']['tags']??[])
			]);
		}
		
        return json_encode($ajax_config, JSON_HEX_APOS);
    }

	public static function GetTermsSlugs($terms){
		if(!isset($terms) || empty($terms)){
			return [];
		}

		return array_reduce($terms,function($result, $term) {
            $result[] = $term->slug;
            return $result;
        }, []);
	}

	public static function GetHeaderFilters($blockData)
    {		
        return $blockData['post_type'] === 'event' ? [
			[
				'input_name' => 'event_time_type',
				'label' => 'Upcoming Events',
				'value' => 'upcoming'
			],
			[
				'input_name' => 'event_time_type',
				'label' => 'Past Events',
				'value' => 'past'
			]
		] : [];
    }

	public static function GetPostsAjax(){
		$args = [
			'post_type' => filter_input(INPUT_GET, 'post_type')? explode(',', filter_input(INPUT_GET, 'post_type', FILTER_UNSAFE_RAW)): self::POST_TYPE,
			'posts_per_page' => filter_input(INPUT_GET, 'posts_per_page')?: self::POSTS_PER_PAGE,
			'page' => filter_input(INPUT_GET, 'page')?: 1,
			's' => filter_input(INPUT_GET, 's')?: false,
			'event-type' => filter_input(INPUT_GET, 'event-type_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'event-type_tax', FILTER_UNSAFE_RAW)): [],
			'format' => filter_input(INPUT_GET, 'format_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'format_tax', FILTER_UNSAFE_RAW)): [],
			'program' => filter_input(INPUT_GET, 'program_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'program_tax', FILTER_UNSAFE_RAW)): [],
			'role' => filter_input(INPUT_GET, 'role_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'role_tax', FILTER_UNSAFE_RAW)): [],
			'news-type' => filter_input(INPUT_GET, 'news-type_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'news-type_tax', FILTER_UNSAFE_RAW)): [],
			'resource-type' => filter_input(INPUT_GET, 'resource-type_tax', FILTER_UNSAFE_RAW)? explode(',', filter_input(INPUT_GET, 'resource-type_tax', FILTER_UNSAFE_RAW)): [],
			'event_time_type' => filter_input(INPUT_GET, 'event_time_type', FILTER_UNSAFE_RAW)?: false,
			'post_tag' => filter_input(INPUT_GET, 'post_tag_tax', FILTER_UNSAFE_RAW) ? explode(',', filter_input(INPUT_GET, 'post_tag_tax', FILTER_UNSAFE_RAW)): [],
			'order_by' => filter_input(INPUT_GET, 'order_by')?: self::ORDER_BY,
		];

		switch(@$args['post_type'][0]){
            case 'event':
                $args['taxonomies_list'] = ['format', 'role', 'program', 'post_tag'];
                break;
            case 'news':
                $args['taxonomies_list'] = ['news-type', 'program', 'post_tag'];
                break; 
            case 'resource':
                $args['taxonomies_list'] = ['resource-type', 'role', 'program', 'post_tag'];
                break; 
			case 'page':
				$args['taxonomies_list'] = ['program', 'post_tag', 'role'];
				break; 
			case 'news_resource':
				$args['post_type'] = ['news', 'resource'];
				$args['taxonomies_list'] = ['news-type', 'resource-type', 'role', 'program', 'post_tag'];
				break; 
            default:
                $args['taxonomies_list'] = [];
        }

		$result = self::GetPosts($args);

		if(is_wp_error($result)){
			wp_send_json_error($result);
		}else{
			wp_send_json_success(self::ConvertDataToHtmlArray($result));
		}
	}

	public static function GetPosts($args = []){
		$result = [];

		//default values
		$posts_per_page = isset($args['posts_per_page']) && $args['posts_per_page'] ? intval($args['posts_per_page']) : self::POSTS_PER_PAGE;
		$page = isset($args['page']) ? intval($args['page']) : 1;
		$post_types = !empty($args['post_type']) ? (is_array($args['post_type'])?$args['post_type']: [$args['post_type']]) : [self::POST_TYPE];

		$order_by_query = [
			'meta_key' => PostSearchProvider::DATE_SORT_FIELD,
			'orderby' => 'meta_value_num',
			'order' => 'DESC'
		];

		if(isset($args['order_by']) && $args['order_by'] === 'title'){
			$order_by_query = [
				'orderby' => 'title',
				'order' => 'ASC',
			];
		}

		$query_args = [
			'combined_query' => [
				'args' => [],
				'posts_per_page' => $posts_per_page,
				'offset' => $posts_per_page * ($page - 1),
				'union' => 'UNION'
			]
		];

		$taxonomies = isset($args['taxonomies_list']) ? $args['taxonomies_list'] : [];

		foreach ($post_types as $post_type) {
			$sub_query = [
				'post_type' => $post_type,
				'posts_per_page' => -1,
				'post_status' => ['publish'],
				'meta_query' => [],
				'tax_query' => []
			];

			/**
			 * TAXONOMIES
			 */
			foreach ($taxonomies as $tax) {
				$sub_query['tax_query'] = isset($args[$tax]) && $args[$tax] ? array_merge($sub_query['tax_query'], [
					[
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $args[$tax],
						'operator' => 'IN'
					]
				]) : $sub_query['tax_query'];
			}

			/**
			 * SPECIAL CASE FOR PROGRAMS
			 * 'PROGRAMS' ARE PAGES WITH A PROGRAM TERM ATTACHED TO IT
			 */
			// Add tax query for program page
			if($post_type === 'page'){
				$sub_query['tax_query'] = array_merge($sub_query['tax_query'], [
					'program_page_clause' => [
						'taxonomy' => 'program',
						'operator' => 'EXISTS'
					]
				]);

				$sub_query['meta_query'] = array_merge($sub_query['meta_query'], [
					[
						'key' => 'hide_selected_program',
						'value' => '1',
						'compare' => '!='
					]
				]);
			}

			if($post_type === 'event'){
			
				$order_by_query['order'] = 'ASC';
				
				if (!empty($args['event_time_type'])) {
					
					$order_by_query['order'] = $args['event_time_type'] === 'upcoming'? 'ASC' : 'DESC';
	
					$sub_query['meta_query'] = array_merge($sub_query['meta_query'], [
						[
							'key' => 'end_date',
							'value' => date('Y-m-d'),
							'compare' => ($args['event_time_type'] === 'upcoming' ? '>':'<=')
						]
					]);
				}
			}

			if(isset($args['s']) && $args['s']){
				$sub_query['s'] = $args['s'];
			}
			
			if (isset($args['post__in'])) {
				$sub_query['post__in'] = $args['post__in'];
			}

			//append to combined query param
			$query_args['combined_query']['args'][] = array_merge($sub_query, $order_by_query);
		}

		$query_args['combined_query'] = array_merge(
			$query_args['combined_query'],
			$order_by_query
		);
		

		/**
		 * MORE INFO HERE: https://github.com/birgire/wp-combine-queries
		 * In order for this to work we need the Combined Queries plugin active
		 */
		// Modify sub fields
		add_filter( 'cq_sub_fields', 'App\Providers\FilterableContentProvider::addCustomSubFields');

		$query = new \WP_Query($query_args);
		wp_reset_postdata();

		//Remove filters added above
		remove_filter( 'cq_sub_fields', 'App\Providers\FilterableContentProvider::addCustomSubFields' );
		
		return ['posts' => $query->posts, 'hasMore'=> ($page < ceil($query->found_posts/$posts_per_page))];
	}

	/**
	 * For Combined Queries, I need to expose the custom meta key to order elements
	 * on the result of the combined query, this fuction adds that SQL statement
	 *
	 * @param string $fields
	 * @return string
	 */
	public static function addCustomSubFields($fields){
		return $fields . ', (SELECT meta_value from wp_postmeta WHERE wp_postmeta.post_id = wp_posts.ID AND wp_postmeta.meta_key = \''.PostSearchProvider::DATE_SORT_FIELD.'\' LIMIT 1) AS meta_value';
	}

	public static function ConvertDataToHtmlArray($data_arr){
		$result = [];

		foreach ($data_arr['posts']?: [] as $i) {
			$card_data = \App\Providers\CardsDataProvider::get($i->ID);
			$card_data['style'] = 'card-type-regular';
			$result[] = view('components.card', $card_data )->render();
		}

		return ['cards' => $result, 'hasMore' => $data_arr['hasMore']];
	}


	public static function GetPostsByType($blockData){
		$result = self::GetPosts($blockData);
		return self::ConvertDataToHtmlArray($result);
	}

}