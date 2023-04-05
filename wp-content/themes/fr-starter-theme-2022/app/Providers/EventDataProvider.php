<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventDataProvider extends ServiceProvider
{

	const POST_TYPE = 'event';
	const POSTS_PER_PAGE = 3;

	public static function GetEvents($args = []){
		$result = [];

		//default values
		$posts_per_page = isset($args['posts_per_page']) && $args['posts_per_page'] ? intval($args['posts_per_page']) : self::POSTS_PER_PAGE;
		$page = isset($args['page_number']) ? intval($args['page_number']) : 0;
		$post_type = self::POST_TYPE;

		$query_args = [
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
			'offset' => $posts_per_page * $page,
			'post_status' => ['publish'],
			'tax_query' => [],
			'meta_query' => [],
			'orderby' => 'start_date_clause',
			'order' => 'ASC'
		];

		if (isset($args['post__in'])) {
            $query_args['post__in'] = $args['post__in'];
        }

		$taxonomies = isset($args['taxonomies']) ? $args['taxonomies'] : [];

		foreach($taxonomies as $t){
			$query_args['tax_query'] = isset($args[$t]) && $args[$t] ? array_merge($query_args['tax_query'], [
				[
					'taxonomy' => $t,
					'field' => 'id',
					'terms' => $args[$t],
					'operator' => 'IN'
				]
			]) : $query_args['tax_query'];
		}

		if (isset($args['upcoming']) && $args['upcoming']) {
			$query_args['meta_query'] = array_merge($query_args['meta_query'], [
				[
					'key' => 'start_date',
					'value' => date('Y-m-d'),
					'compare' => '>'
				]
			]);
		}

		// Sorting on start date
		$query_args['meta_query'] = array_merge($query_args['meta_query'], [
			'start_date_clause' => [
				'key' => 'start_date',
				'compare' => 'EXISTS'
			]
		]);

		$query = new \WP_Query($query_args);
		wp_reset_postdata();

		return $query->posts ?: $result;
	}

	public static function ConvertDataToHtmlArray($data_arr){
		$result = [];

		foreach ($data_arr?: [] as $event) {
			$result[] = view('components.event-card', [
				'preview' => false,
				'title' => $event['title'], 
				'date' => $event['excerpt'],
				'image_url' => $event['image'] ? $event['image']['url'] : false,
				'image_alt' => $event['image'] ? $event['image']['alt'] : false,
				'url' => $event['permalink'] && is_array($event['permalink']) ? $event['permalink']['url'] : false,
				'url_target' => $event['permalink'] && is_array($event['permalink']) ? $event['permalink']['target'] : false,
				'tag' => $event['tag'] ?: []
			])->render();
		}

		return $result;
	}

	public static function GetUpcomingEvents($args = []){
		switch(@$args['event_section']['source']){
			case 'manual':
				if(empty($args['event_section']['manual_events'])) return [];
				$args['post__in'] = $args['event_section']['manual_events'];
				break;
			case 'upcoming':
				$args['taxonomies'] = [
					'format',
					//'event-type',
					'program',
					'role'
				];
				$args['upcoming'] = true;
				$args = array_merge($args, $args['event_section']['taxonomy_filters']);
				break;
			default:
				$args['taxonomies'] = [];
		}

		return array_map(function($event) {
			return [
				'id' => $event->ID
			];
		}, self::GetEvents($args));
	}

	public static function GetSectionEvents($eventIds){
		if(empty($eventIds)) return [];

		$args['post__in'] = $eventIds;

		return array_map(function($event) {
			return [
				'id' => $event->ID
			];
		}, self::GetEvents($args));
	}
}
