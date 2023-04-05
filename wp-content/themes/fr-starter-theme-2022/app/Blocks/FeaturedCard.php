<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FeaturedCard extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Featured Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Featured Cards block.';

    /**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-featured-card';

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
        'attributes' => [
            'preview_image' => 'FeaturedCards.png'
        ],

    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        $result = [
            'items' => get_field('items'),
            'Featured_card_title_content' => get_field('Featured_card_title_content'),
        ];

        if($this->preview && (!$result['items'] && !$result['Featured_card_title_content'])){
            $result = [
                'Featured_card_title_content' => 'Example Data',
                'items' => [
                    [
                        'Title' => 'Example: Start editing here.',
                        'Featured_image' => false,
                        'Color' => '#1E2642',
                        'Button' => 'Example Link',
                        'Color' => '#EF3B48',
                        'label_color' => 'white',
                        'Button_link' => [
                            'url' => 'javascript:void(0)',
                            'target' => ''
                        ]
                    ]
                ]
            ];
        }

        return $result;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $featuredCard = new FieldsBuilder('featured_card');

        $featuredCard
            ->addWysiwyg('Featured_card_title_content',[
                'required' => 1,
                'default_value' => '<h3>Featured Cards Title</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>'
            ])
            ->addRepeater('items', [
                'layout' => 'block'
            ])
            ->addTrueFalse('add_short_description', [
                'label' => 'Add short description?',
            ])
                ->addText('Title', [
                    'required' => 1,
                    'wrapper' => [
                        'width' => 75
                    ]
                ])
                 
                 ->addText('small_descripiton',[
                    'wrapper' => [
                        'width' => 75
                    ]
                 ])
                   ->conditional('add_short_description', '==', '1')
                ->addImage('Featured_image', [
                    'wrapper' => [
                        'width' => 25
                    ],
                    'preview_size' => 'thumbnail',
                ])
                ->addText('Button', [
                    'required' => 1,
                    'label' => 'Button Label',
                    'wrapper' => [
                        'width' => 34
                    ],
                ])
                ->addLink('Button_link', [
                    'required' => 1,
                    'wrapper' => [
                        'width' => 22
                    ],
                ])
                ->addColorPicker('Color', [
                    'label' => 'Accent Color',
                    'wrapper' => [
                        'width' => 22
                    ],
                ])
                ->addRadio('label_color', [
                    'label' => 'Button Label Color',
                    'choices' => [
                        'white' => 'White',
                        'deep-navy' => 'Deep Navy'
                    ],
                    'default_value' => 'white',
                    'wrapper' => [
                        'width' => 22
                    ],
                ])
            ->endRepeater();

        return $featuredCard->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        // return get_field('items') ?: $this->example['items'];

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