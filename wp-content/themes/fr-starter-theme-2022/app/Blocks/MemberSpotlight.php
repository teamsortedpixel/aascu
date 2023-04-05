<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class MemberSpotlight extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Member Spotlight';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Member Spotlight block.';

     /**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-member-spotlight';

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
        'members' => [
            [
                'member_content' => '<h4>Sam Houston State University Center For Community Engagement Receives National Recognition</h4>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ncididunt ut labore et dolore magna aliqua.
                <h5>PROGRAMS</h5><ul><li>Lorem ipsum dolor sit amet consectetur adipiscing elit</li><li>Lorem ipsum dolor sit amet consectetur adipiscing elit</li></ul>',
                'member_image' => [
                    'url' => 'https://picsum.photos/seed/picsum/516/416',
                    'alt' => 'Example Image'
                ],
                'impact_title' => 'Impact',
                'member_impact' => [
                    [
                        'impact_content' => '<h1>100%</h1>Lorem ipsum dolor sit amet consectetur'
                    ],
                    [
                        'impact_content' => '<h1>2M</h1>Lorem ipsum dolor sit amet consectetur'
                    ],
                    [
                        'impact_content' => '<h1>4700</h1>Lorem ipsum dolor sit amet consectetur'
                    ]
                ],
                'testimonial_organization' => 'Organization'
            ]
        ],
        'attributes' => [
            'preview_image' => 'MemberSpotlight.png'
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        if($this->preview && empty(get_field('members'))){
            return $this->example;
        }

        return [
            'members' => get_field('members')?: [],
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $memberSpotlight = new FieldsBuilder('member_spotlight');

        $memberSpotlight
            ->addRepeater('members',[
                'layout' => 'block',
                'min' => 1
            ])
                ->addImage('member_image', [
                    'required' => 1,
					'wrapper' => [
						'width' => 35
					]
				])
                ->addWysiwyg('member_content', [
                    'required' => 1,
                    'default_value' => '<h4>Sam Houston State University Center For Community Engagement Receives National Recognition</h4>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ncididunt ut labore et dolore magna aliqua.
                    <h5>PROGRAMS</h5><ul><li>Lorem ipsum dolor sit amet consectetur adipiscing elit</li><li>Lorem ipsum dolor sit amet consectetur adipiscing elit</li></ul>',
					'wrapper' => [
						'width' => 65
					]
				])

                ->addText('impact_title', [
                    'default_value' => 'Impact'
				])
                ->addRepeater('member_impact',[
                    'max' => 3,
                    'min' => 3,
                    'layout' => 'block'
                ] )
                    ->addWysiwyg('impact_content', [
                        'default_value' => '<h1>100%</h1>Lorem ipsum dolor sit amet consectetur'
                    ])
                ->endRepeater()
            ->endRepeater();

        return $memberSpotlight->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return get_field('members') ?: $this->example['members'];
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
