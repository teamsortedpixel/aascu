<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CardsDataProvider extends ServiceProvider
{
	const EVENT_CTA_LABEL_DEFAULT = 'Register today.';

	public static function get($post){
		$post_type = get_post_type($post);

		$data = [
			'post_type' => $post_type,
			'permalink' => [
				'url' => get_the_permalink($post),
				'target' => '',
				'label' => 'Read more.'
			],
			'title' => get_the_title($post),
			'excerpt' => get_the_excerpt($post),
			'program_tags' => self::generateProgramTags($post),
			'program' => self::getProgram($post),
			'classes' => false,
			'image' => false
		];

		//target logic specified on AASCM-514
		if(in_array($post_type, ['event', 'news', 'resource'])){
			$data['permalink']['target'] = \App\Providers\PostLinkRedirectProvider::PostLinkRedirectGetLinkTarget($post) ?: '';
		}

		if(in_array($post_type, ['page', 'event', 'news', 'resource'])){
			$image =  wp_get_attachment_url(get_post_thumbnail_id($post), 'thumbnail') ? wp_get_attachment_url(get_post_thumbnail_id($post), 'thumbnail') : wp_get_attachment_image_url(self::getHeroField('image', $post), 'large');
			$hero_content = self::getHeroField('content', $post);

			if($image){
				$data = array_merge($data, [
					'image' => [
						'url' => $image,
						'alt' => $data['title'],
						'ID' => \attachment_url_to_postid($image),
						'caption' => wp_get_attachment_caption(\attachment_url_to_postid($image))
					]
				]);
			}

			//Hero data
			$data = array_merge($data, [
				'hero_content' => $hero_content
			]);
		}

		if(in_array($post_type, ['news', 'resource'])){
			$data = array_merge($data, [
				'hide_date' => get_field('hide_date', $post)??false,
				'publication_date' => \get_the_date('F j, Y', $post),
			]);
		}

		if($post_type == 'resource'){
			$data = array_merge($data, [
				'resource_type' => self::getResourceType($post),
				'author' => self::getAuthor($post)
			]);
		}

		if($post_type == 'event'){
			$data = array_merge($data, [
				'format' => self::getFormat($post),
				'formatted_date' => self::getEventDateString($post),
				'formatted_time' => self::getLocationOrTimeString($post)
			]);
			$data['permalink']['label'] = get_field('cta_label', $post) ?: self::EVENT_CTA_LABEL_DEFAULT;
		}

		return $data;
	}

	/**
	 * Generates an array of HTML tags based on the program terms associated
	 * $post = the WP_Post object or ID of post
	 * $wrapper_class = if added it will add a div that wraps the tags with the class passed as the parameter
	 *
	 * @param [type] $post
	 * @param string $wrapper_class
	 * @return string $result
	 */
	public static function generateProgramTags($post, $wrapper_class = '') {
		$result = '';
		$tags = [];

		if(get_field('hide_selected_program', $post)) return $result;

		if($post){
			$primary_program = self::getPrimaryTermByTaxonomy($post, 'program');

			if($primary_program){
				$styles = 'color:'.get_field('text_color', 'program_' . $primary_program->term_id) .';background-color:'.get_field('accent_color', 'program_' . $primary_program->term_id) .';';
				$index_link = self::getIndexPageLink(get_post_type($post), $primary_program->slug);
				$attr = $index_link ? 'href="'.$index_link.'"' : '';

				$tags[] = '<a '.$attr.' style="'.$styles.'" class="badge program-badge">'.$primary_program->name.'</a>';
			}
		}

		$result = implode('', $tags);

		if($wrapper_class && strlen($wrapper_class)){
			$result = '<div class="'.$wrapper_class.'">'.$result.'</div>';
		}

		return $result;
	}

	public static function getPrimaryTermByTaxonomy($post_id, $taxonomy){
		$result = false;

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$primary_id = function_exists('yoast_get_primary_term_id') ? yoast_get_primary_term_id($taxonomy, $post_id) : false;

		if($primary_id){
			$result = get_term_by('id', $primary_id, $taxonomy);
		}else{
			$terms = wp_get_post_terms($post_id, $taxonomy);
			$result = $terms && !is_wp_error($terms) ? $terms[0] : false;
		}

		return $result;
	}

	public static function getFormat($post_id){
		$result = false;

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$format = wp_get_post_terms($post_id, 'format')[0] ?? false;

		if($format){
			$result = [
				'name' => $format->name,
				'term_id' => $format->term_id,
				'icon' => get_field('icon', 'format_' . $format->term_id)
			];
		}

		return $result;
	}

	public static function getAuthor($post_id){
		$result = false;

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		if(get_field('show_author', $post_id)){
			$result = get_field('resource_author', $post_id);
		}

		return $result;
	}

	public static function getResourceType($post_id){
		$result = false;

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$rType = wp_get_post_terms($post_id, 'resource-type')[0] ?? false;

		if($rType){
			$icon = get_field('icon', 'resource-type_' . $rType->term_id);
			$result = [
				'name' => $rType->name,
				'term_id' => $rType->term_id,
			];

			if($icon){
				$result['icon'] = $icon;
				$result['icon_html'] =  preg_replace("/<\\?xml.*\\?>/", '',file_get_contents($icon['url']));
				$result['icon_instance_id'] = uniqid('icon-'); //for styling the icon in the card
			}
		}

		return $result;
	}

	public static function getProgram($post_id){
		$result = false;

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$program = self::getPrimaryTermByTaxonomy($post_id, 'program');

		if($program){
			$result = [
				'name' => $program->name,
				'term_id' => $program->term_id,
				'accent_color' => get_field('accent_color', 'program_' . $program->term_id),
				'text_color' => get_field('text_color', 'program_' . $program->term_id),
			];
		}

		return $result;
	}


	public static function getHeroField($fieldName, $post_id){
		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}
		$post = get_post($post_id);
		$blocks = parse_blocks($post->post_content);
		$hero_block_name = 'acf/hero';

		if($post->post_type == 'event'){
			$hero_block_name = 'acf/event-hero';
		}

		if(in_array($post->post_type, ['news', 'resource'])){
			$hero_block_name = 'acf/news-resource-hero';
		}

		foreach ($blocks as $block) {
			if($block['blockName'] == 'acf/fr-page-builder'){
				if($block['innerBlocks'] && count($block['innerBlocks'])){
					foreach ($block['innerBlocks'] as $innerBlock) {
						if($innerBlock['blockName'] == $hero_block_name){
							if(isset($innerBlock['attrs']['data'][$fieldName])){
								return $innerBlock['attrs']['data'][$fieldName];
							}
						}
					}
				}
			}
		}
	}

	public static function getDateArray($date_field, $format = 'Y-m-d H:i:s'){

		if(!$date_field) return [];

		$timestamp = \DateTime::createFromFormat($format, $date_field)->getTimestamp();

		//AASCM-550
		$month = date('M', $timestamp);

		$result = [
			'timestamp' => $timestamp,
			'day' => date('j', $timestamp),
			'month' => $month !== 'May' ? $month . '.' : $month,
			'year' => date('Y', $timestamp),
			'hour' => date('g', $timestamp),
			'minute' => date('i', $timestamp),
			'am_pm' => date('A', $timestamp)
		];

		return $result;
	}

	public static function getEventDateString($post_id){

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$start_date = get_field('start_date', $post_id);
		$end_date = get_field('end_date', $post_id);

		return self::getStartAndEndDateString($start_date, $end_date);
	}

	public static function getLocationOrTimeString($post_id){
		$result = '';

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$hide_time = get_field('hide_time', $post_id);
		$location = get_field('location', $post_id);

		if($hide_time){
			$result = $location;
		}else{
			$result = self::getEventTimeString($post_id);
		}

		return $result;
	}

	public static function getEventTimeString($post_id){

		if($post_id instanceof \WP_Post) {
			$post_id = $post_id->ID;
		}

		$start_date = get_field('start_date', $post_id);
		$end_date = get_field('end_date', $post_id);
		$timezone = get_field('timezone_label', $post_id);

		return self::getStartAndEndTimeString($start_date, $end_date, $timezone);
	}

	public static function getStartAndEndDateString($start_date, $end_date){
		$result = false;


		if(is_null($start_date) || !strlen($start_date)) return '';


		$start = self::getDateArray($start_date);
		$end = self::getDateArray($end_date);

		if(empty($end)){
			$result = $start['month'] . ' ' . $start['day'] . ('<span class="year">, '.$start['year'].'</span>');
		}
		else{
			if($start['year'] === $end['year'] && $start['month'] === $end['month'] && $start['day'] === $end['day']){
				$result = $start['month'] . ' ' . $start['day'] . ('<span class="year">, '.$start['year'].'</span>');
			}else if($start['year'] === $end['year'] && $start['month'] === $end['month']){
				$result =  $start['month'] . ' ' . $start['day'] . '-' . $end['day'] . ('<span class="year">, '.$start['year'].'</span>');
			}else if($start['year'] === $end['year']){
				$result = $start['month'] . ' ' . $start['day'] . '-' . $end['month'] . ' ' . $end['day'] . ('<span class="year">, '.$start['year'].'</span>');
			}else{
				$result = $start['month'] . ' ' . $start['day'] . ' - ' . $end['month'] . ' ' . $end['day'] . ('<span class="year">, '.$end['year'].'</span>');
			}
		}

		return $result;
	}

	public static function getStartAndEndTimeString($start_date, $end_date, $timezone){
		$result = false;

		if(is_null($start_date) || !strlen($start_date)) return '';

		$start = self::getDateArray($start_date);
		$end = self::getDateArray($end_date);


		$result = $start['hour'].':'.$start['minute'].$start['am_pm'].(empty($end)?'':('-'. $end['hour'].':'.$end['minute'].$end['am_pm'])).' '.$timezone;

		return $result;
	}

	/**
	 * Returns the page object for the index page for each post type
	 *
	 * @param string $post_type
	 * @return WP_Post
	 */
	public static function getIndexPageByPostType($post_type){
		$field_name = 'program_pages_index_page';

		switch ($post_type) {
			case 'news':
				$field_name = 'news_index_page';
				break;
			case 'resource':
				$field_name = 'resources_index_page';
				break;
			case 'event':
				$field_name = 'events_index_page';
				break;
			default:
				$field_name = 'program_pages_index_page';
				break;
		}

		return get_field($field_name, 'option');
	}

	/**
	 * Returns the link of the index page with the program parameter .
	 * If no index is set in the backend for the post type it will just return the text
	 *
	 * @param string $linkLabel
	 * @param string $post_type
	 * @param string $program_name
	 * @return string $result
	 */
	public static function getIndexPageLink($post_type = 'page', $program_slug = ''){
		$result = false;
		$index_page = self::getIndexPageByPostType($post_type);

		if(!$index_page){
			return $result;
		}

		$result = get_the_permalink($index_page) . '?program_tax='. $program_slug;

		return $result;
	}
}
