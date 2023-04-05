<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PeoplesDataProvider extends ServiceProvider
{

	const POST_TYPE = 'people';
	const POSTS_PER_PAGE = 8;

	public static function GetPosts($args = []){
		$result = [];

		//default values
		$posts_per_page = isset($args['posts_per_page']) && $args['posts_per_page'] ? intval($args['posts_per_page']) : self::POSTS_PER_PAGE;
		$page = isset($args['page_number']) ? intval($args['page_number']) : 0;
		$post_type = isset($args['post_type']) ? $args['post_type'] : self::POST_TYPE;

		$query_args = [
			'post_type' => $post_type,
			'posts_per_page' => $posts_per_page,
			'offset' => $posts_per_page * $page,
			'post_status' => ['publish'],
			'tax_query' => [],
			'meta_query' => []
		];

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

		$query_args['meta_query'] = array_merge($query_args['meta_query'], [
			'relation' => 'AND',
			'lastname_clause' => [
				'key' => 'lastname'
			],
			'firstname_clause' => [
				'key' => 'firstname'
			]
		]);

		$query_args = array_merge($query_args, [
			'orderby' => [
				'lastname_clause' => 'ASC',
				'firstname_clause' => 'ASC',
			]
		]);

		if (isset($args['post__in'])) {
            $query_args['post__in'] = $args['post__in'];
			$query_args['orderby'] = 'post__in';
        }

		$query = new \WP_Query($query_args);
		wp_reset_postdata();

		return $query->posts ?: $result;
	}

	public static function GetPeoples($args = []){
		switch($args['source']){
			case 'manual':
				$args['post__in'] = $args['peoples'];
				break;
			case 'taxonomies':
				$args['taxonomies'] = [
					'department',
					'program'
				];
				break;
			default:
				$args['taxonomies'] = [];
				$args['program'] = [];
				$args['department'] = [];
		}

		$args['posts_per_page'] = isset($args['show_all'])?($args['show_all']? -1 : $args['max_count']):$args['max_count'];

		$peoples = self::GetPosts($args);

		$result = [];
		
		foreach($peoples as $member){
			$bio = get_field('bio', $member->ID);
			$phone = get_field('phone', $member->ID);
			$email = get_field('email', $member->ID);
			$socialLinks = get_field('social_links', $member->ID);

			$result[] = [
				'id' => $member->ID,
				'permalink' => [
					'url' => get_the_permalink($member),
					'target' => '',
					'label' => ''
				],
				'profile_photo' => get_field('profile_photo', $member->ID),
				'firstname' => get_field('firstname', $member->ID),
				'lastname' => get_field('lastname', $member->ID),
				'title' => get_field('title', $member->ID),
				'description' => get_field('description', $member->ID),
				'has_empty_bio' => (empty($bio) && empty($phone) && empty($email) && empty($socialLinks))
			];
		}

		return $result;
	}
}
