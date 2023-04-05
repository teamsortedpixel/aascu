<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FeaturedOpportunities extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Featured Opportunities';

    /**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-featured-opportunities';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Featured Opportunities block.';

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
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'items' => [
            [
                'featured_opp_tab_title' => 'Lorem Ipsum',
                'featured_opp_tab_image' => [
                    'url' => 'https://picsum.photos/200/110',
                ],
                'featured_opp_tab_content' => '<h2>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do</h2>',
                'featured_opp_tab_below_content' => 'Lorem ipsum dolor sit amet'

            ]
        ],
        'featured_title' => 'Featured Title',
        'attributes' => [
            'preview_image' => 'FeaturedContent.png'
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
            'module_id' => uniqid('feat-opp-'),
            'items' => get_field('items')?:[],
            'featured_title' => get_field('featured_opp_custom_title'),
        ];

        if($this->preview && empty($blockData['items'])){
            return array_merge($blockData, $this->example);
        }

        return $blockData;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $featuredOpportunities = new FieldsBuilder('featured_opportunities');

        $featuredOpportunities

        ->addText('featured_opp_custom_title', [
            'wrapper' => [
                'width' => 100
            ],
            'default_value' => 'Featured Opportunities Block Title'
        ])
            ->addRepeater('items', [
                'min' => 1,
                'max' => 3,
                'layout' => 'block'
             ])
                ->addText('featured_opp_tab_title', [
					'wrapper' => [
						'width' => 100
                    ],
                    'default_value' => 'Lorem Ipsum',
                    'required' => 1
				])
                ->addWysiwyg('featured_opp_tab_content', [
					'wrapper' => [
						'width' =>100
                    ],
                    'default_value' => '<h2>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do</h2>[cta-button label="Test Cta" external_url="https://freerange.com" style="primary"]',
                    'required' => 1
				])
                ->addImage('featured_opp_tab_image', [
					'wrapper' => [
						'width' => 40
                    ],
				])
                ->addText('featured_opp_tab_below_content', [
                    'label' => "Image caption",
					'wrapper' => [
						'width' => 60
                    ],
                    'default_value' => 'Lorem ipsum dolor sit amet',
				])
            ->endRepeater();

        return $featuredOpportunities->build();
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
