<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class TestimonialSlider extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Testimonial Slider';

    /**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-testimonial-slider';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Testimonial Slider block.';

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
    public $icon = ' fricon dashicons-format-quote';

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
        'testimonials' => [
            [
                'testimonial_content' => '<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</h4>',
                'testimonial_image' => [
                    'url' => 'https://picsum.photos/seed/picsum/516/416',
                    'alt' => 'Example Image'
                ],
                'testimonial_name' => 'Firstname Lastname',
                'testimonial_title' => 'Title',
                'testimonial_organization' => 'Organization'
            ]
        ],
        'attributes' => [
            'preview_image' => 'testimonial-slider.png'
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        if($this->preview && empty(get_field('testimonials'))){
            return $this->example;
        }

        return [
            'testimonials' => get_field('testimonials')?:[]
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $testimonialSlider = new FieldsBuilder('testimonial_slider');

        $testimonialSlider
        ->addRepeater('testimonials',[
            'layout' => 'block',
            'min' => 1
         ])
            ->addImage('testimonial_image', [
                'wrapper' => [
                    'width' => 35
                ],
                'required' => 1
            ])
            ->addWysiwyg('testimonial_content', [
                'wrapper' => [
                    'width' => 65
                ],
                'required' => 1,
                'default_value' => '<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</h4>'
            ])
            ->addText('testimonial_name', [
                'default_value' => 'Firstname Lastname'
            ])
            ->addText('testimonial_title', [
                'default_value' => 'Title'
            ])
            ->addText('testimonial_organization', [
                'default_value' => 'Organization'
            ])


        ->endRepeater();

        return $testimonialSlider->build();
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
