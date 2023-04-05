<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\CtaButtonFields;

class PeopleList extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'People List';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-people-list';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A simple People List block.';

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
	 * The block preview example data.
	 *
	 * @var array
	 */
	public $example = [
		'max_count' => 10,
		'peoples' => [
			[
				'firstname' => 'Eiusmod',
				'lastname' => 'Loremipsum',
				'title' => 'Title sed do eiusmod tempor consectetur adipiscing',
				'description' => 'Organization Lorem ipsum dolor sit amet consectetur adipiscing elit sed do',
				'profile_photo' => false
			],
			[
				'firstname' => 'Eiusmod',
				'lastname' => 'Loremipsum',
				'title' => 'Title sed do eiusmod tempor consectetur adipiscing',
				'description' => 'Organization Lorem ipsum dolor sit amet consectetur adipiscing elit sed do',
				'profile_photo' => false
			],
			[
				'firstname' => 'Eiusmod',
				'lastname' => 'Loremipsum',
				'title' => 'Title sed do eiusmod tempor consectetur adipiscing',
				'description' => 'Organization Lorem ipsum dolor sit amet consectetur adipiscing elit sed do',
				'profile_photo' => false
			],
			[
				'firstname' => 'Eiusmod',
				'lastname' => 'Loremipsum',
				'title' => 'Title sed do eiusmod tempor consectetur adipiscing',
				'description' => 'Organization Lorem ipsum dolor sit amet consectetur adipiscing elit sed do',
				'profile_photo' => false
			]
		],
		'attributes' => [
            'preview_image' => 'people-list.png'
        ],
	];

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		$blockData = [
			'preview' => $this->preview,
			'show_all' => get_field('show_all'),
			'max_count' => get_field('max_count'),
			'source' => get_field('source'),
			'peoples' => get_field('peoples'),
			'department' => get_field('department'),
			'program' => []
		];

		$data = array_merge($blockData, [
			'peoples' => \App\Providers\PeoplesDataProvider::GetPeoples($blockData)
		]);

		if($this->preview && count($data['peoples']) == 0){
			return $this->example;
		}

		return $data;
	}

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$hero = new FieldsBuilder('people_list');

		$hero
			->addSelect('source', [
				'allow_null' => 0,
				'choices' => [
					'all' => 'All',
					'taxonomies' => 'Department/Program',
					'manual' => 'manual'
				],
				'default' => 'all',
				'wrapper' => [
					'width' => 60
				]
			])
			->addTrueFalse('show_all', [
				'label' => 'Show all profiles?',
                'default_value' => 1,
                'wrapper' => [
                    'width' => 20
                ]
            ])
			->addNumber('max_count', [
				'label' => 'Profile Count Limit',
				'min' => 1,
				'max' => 150,
				'default_value' => 1,
				'wrapper' => [
					'width' => 20
				]
			])
			->conditional('show_all', '==', 0)
			->addPostObject('peoples', [
				'allow_null' => 1,
				'multiple' => 1,
				'post_type' => [
					'people'
				],
				'return_format' => 'id',
			])
			->conditional('source', '==', 'manual')
			->addTaxonomy('department', [
                'label' => 'Groups',
                'taxonomy' => 'department',
                'field_type' => 'multi_select',
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'multiple' => 1,
                'return_format' => 'id',
				'wrapper' => [
					'width' => 50
				]
            ])
            ->conditional('source', '==', 'taxonomies');

		return $hero->build();
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
