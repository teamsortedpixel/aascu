<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\CardGridFilters;
use App\Providers\PostSearchProvider;
use App\View\Components\Card;

class CardGrid extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'Card Grid';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-card-grid';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A simple Card Grid block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'fr-page-builder-content-blocks';

	/**
	 * The block icon.
	 *
	 * @var string|array
	 */
	public $icon = '';

	/**
	 * The block keywords.
	 *
	 * @var array
	 */
	public $keywords = [];

	/**
	 * The block post type allow list.
	 *
	 * @var array
	 */
	public $post_types = [];

	/**
	 * The parent block type allow list.
	 *
	 * @var array
	 */
	public $parent = ['acf/block-container'];


	/**
	 * The default block mode.
	 *
	 * @var string
	 */
	public $mode = 'preview';

	/**
	 * The default block alignment.
	 *
	 * @var string
	 */
	public $align = '';

	/**
	 * The default block text alignment.
	 *
	 * @var string
	 */
	public $align_text = '';

	/**
	 * The default block content alignment.
	 *
	 * @var string
	 */
	public $align_content = '';

	/**
	 * The supported block features.
	 *
	 * @var array
	 */
	public $supports = [
		'align' => false,
		'align_text' => false,
		'align_content' => false,
		'full_height' => false,
		'anchor' => true,
		'mode' => 'edit',
		'multiple' => true,
		'jsx' => true,
	];

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return $this->blockData();
	}

	public $example = [
        'attributes' => [
            'preview_image' => 'card-grid.png'
        ],
	];

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$cardGrid = new FieldsBuilder('card_grid');

		$cardGrid
			->addRadio('source', [
				'choices' => [
					'posts' => 'Manually Selected',
					'from_filters' => 'Dynamically Pulled from Filters',
				],
				'layout' => 'horizontal',
				'default_value' => 'manual',
				'wrapper' => [
					'width' => 60
				]
			])
			->addSelect('order_by', [
                'choices' => [
                    'date' => 'Publish Date',
                    'title' => 'Title'
                ],
                'multiple' => 0,
                'allow_null' => 0,
                'default' => 'date',
                'wrapper' => [
					'width' => 20
				]
            ])
			->addTrueFalse('use_slider', [
				'label' => 'Enable Slider?',
				'wrapper' => [
					'width' => 20
				]
			])
			->addPostObject('manually_selected_posts', [
				'post_type' => ['news', 'resource', 'event', 'page'],
				'multiple' => 1
			])
				->conditional('source', '==', 'posts')
			->addGroup('filters_configuration')
				->conditional('source', '==', 'from_filters')
				->addFields($this->get(CardGridFilters::class))
			->endGroup();

		return $cardGrid->build();
	}

	/**
	 * Return the block fields.
	 *
	 * @return array
	 */
	public function blockData()
	{
		$source = get_field('source');

		switch ($source) {
			case 'posts':
				$cards = $this->generateCardDataFromPostArray(get_field('manually_selected_posts') ?: []);
				break;
			case 'from_filters':
				$cards = $this->getCardsFromFilters(get_field('filters_configuration')['taxonomies'], $this->getPostTypesArrayByCodedName(get_field('filters_configuration')['post_types']), get_field('filters_configuration')['card_count'], get_field('order_by'));
				break;
			default:
				$cards = [];
				break;
		}

		$result =  [
			'cards' => $cards,
			'source' => get_field('source'),
			'order_by' => get_field('order_by'),
			'use_slider' => count($cards) >= 3 ? get_field('use_slider') : false,
			'extra_css' => $this->postTypesIncludeProgram(@get_field('filters_configuration')['post_types']) ? 'has-program-cards' : ''
		];

		if($this->preview && ( get_field('manually_selected_posts') == false && (get_field('filters_configuration') == NULL || get_field('filters_configuration')['post_types'] == []) )){
			$result = $this->exampleData();
		}

		return $result;
	}

	public function getCardsFromFilters($taxonomies, $post_types, $card_count, $order_by = 'date'){
		$result = [];

		$order_by_query = [
			'meta_key' => PostSearchProvider::DATE_SORT_FIELD,
			'orderby' => 'meta_value_num',
			'order' => $this->getOrderByCardinality($post_types, $taxonomies)
		];

		if(isset($order_by) && $order_by === 'title'){
			$order_by_query = [
				'orderby' => 'title',
				'order' => 'ASC',
			];
		}

		$args = [
			'combined_query' => array_merge([
				'args' => [],
				'posts_per_page' => $card_count,
				'offset' => 0,
				'union' => 'UNION'
			], $order_by_query)
		];

		foreach ($post_types as $p) {
			$sub_query = array_merge([
				'post_type' => $p,
				'posts_per_page' => -1,
				'meta_query' => [],
				'tax_query' => []
			], $order_by_query);

			$taxonomies_for = [];
			$metas_for = [];

			switch ($p) {
				case 'page':
					$taxonomies_for = ['program'];
					break;
				case 'news':
					$taxonomies_for = ['program', 'post_tag', 'news-type'];
					break;
				case 'resource':
					$taxonomies_for = ['program', 'post_tag', 'resource-type', 'role'];
					break;
				case 'event':
					$taxonomies_for = ['program', 'post_tag', 'format', 'role'];
					$metas_for = ['event_status'];
					break;
				default:
					break;
			}

			/**
			 * TAXONOMIES
			 */
			foreach ($taxonomies_for as $tax) {
				$sub_query['tax_query'] = isset($taxonomies[$tax]) && $taxonomies[$tax] ? array_merge($sub_query['tax_query'], [
					[
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $taxonomies[$tax],
						'operator' => 'IN'
					]
				]) : $sub_query['tax_query'];
			}

			/**
			 * SPECIAL CASE FOR PROGRAMS
			 * 'PROGRAMS' ARE PAGES WITH A PROGRAM TERM ATTACHED TO IT
			 */
			if($p == 'page'){
				$sub_query['tax_query'] = array_merge($sub_query['tax_query'], [
					[
						'taxonomy' => 'program',
						'operator' => 'EXISTS'
					]
				]);
			}

			/**
			 * META FIELDS
			 */
			foreach ($metas_for as $meta) {
				if($meta == 'event_status'){
					$sub_query['meta_query'] = isset($taxonomies[$meta]) && in_array($taxonomies[$meta], ['current', 'past']) ? array_merge($sub_query['meta_query'], [
						[
							'key' => PostSearchProvider::DATE_SORT_FIELD,
							'value' => (new \DateTime())->getTimestamp(),
							'compare' => $taxonomies[$meta] == 'current' ? '>' : '<=',
							'type' => 'NUMERIC'
						]
					]) : $sub_query['meta_query'];
				}
			}

			if($p == 'page'){
				$sub_query['meta_query'] = array_merge($sub_query['meta_query'], [
					[
						'key' => 'hide_selected_program',
						'value' => '1',
						'compare' => '!='
					]
				]);
			}
			

			//append to combined query param
			$args['combined_query']['args'][] = $sub_query;

			error_log(json_encode($sub_query));
		}

		/**
		 * MORE INFO HERE: https://github.com/birgire/wp-combine-queries
		 * In order for this to work we need the Combined Queries plugin active
		 */
		// Modify sub fields
		add_filter( 'cq_sub_fields', 'App\Blocks\CardGrid::addCustomSubFields');

		$query = new \WP_Query($args);
		wp_reset_postdata();

		//Remove filters added above
		remove_filter( 'cq_sub_fields', 'App\Blocks\CardGrid::addCustomSubFields' );
		
		foreach ($query->posts as $p) {
			$result[] = $p;
		}

		return $this->generateCardDataFromPostArray($result);
	}

	public function generateCardDataFromPostArray($data = []){
		$result = [];

		foreach ($data ?: [] as $i) {
			$card = new \App\View\Components\Card($i, false, 'condensed');
			$result[] = $card->render()->with($card->data());
		}

		return $result;
	}

	public function getOrderByCardinality($post_types, $taxonomies){
		return is_array($post_types) && in_array('event', $post_types) && is_array($taxonomies) && $taxonomies['event_status'] == 'current' ? 'ASC' : 'DESC';
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

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function exampleData() {
		$result = [
			'cards' => [
				view('components.card', [
					'post_type' => 'news',
					'style' => 'condensed',
					'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor. Dolor sit amet, consectetur.',
					'excerpt' => '',
					'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
					'permalink' => [
						'label' => 'Read more.',
						'url' => 'javascript:void(0)',
						'target' => false
					],
					'image' => false,
					'format' => false,
					'program' => false,
					'resource_type' => false,
					'formatted_date' => false,
					'formatted_time' => false,
					'publication_date' => 'January 1, 2023',
					'author' => false,
				])->render(),
				view('components.card', [
					'post_type' => 'resource',
					'style' => 'condensed',
					'title' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit.',
					'excerpt' => '',
					'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
					'permalink' => [
						'label' => 'Read more.',
						'url' => 'javascript:void(0)',
						'target' => false
					],
					'image' => false,
					'format' => false,
					'program' => false,
					'resource_type' => false,
					'formatted_date' => false,
					'formatted_time' => false,
					'publication_date' => false,
					'author' => 'Author/<br>Institution Name ',
				])->render(),
				view('components.card', [
					'post_type' => 'news',
					'style' => 'condensed',
					'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.',
					'excerpt' => '',
					'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
					'permalink' => [
						'label' => 'Read more.',
						'url' => 'javascript:void(0)',
						'target' => false
					],
					'image' => false,
					'format' => false,
					'program' => false,
					'resource_type' => false,
					'formatted_date' => false,
					'formatted_time' => false,
					'publication_date' => 'January 1, 2023',
					'author' => false,
				])->render() 
			],
			'source' => 'posts',
			'use_slider' => false
		];

		return $result;
	}

	public function getPostTypesArrayByCodedName($coded_name){
		$result = [$coded_name];

		if($coded_name == 'page__program' ){
			$result = ['page'];
		}else if ($coded_name == 'news__resources'){
			$result = ['news', 'resource'];
		}

		return $result;
	}

	public function postTypesIncludeProgram($post_types){
		return isset($post_types) && $post_types == 'page__program';
	}

	/**
	 * Assets to be enqueued when rendering the block.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		//
	}
}
