<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ResponsiveImage extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Responsive Image';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-responsive-image';


    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Responsive Image block.';

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
    public $icon = ' fricon dashicons-format-image';

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

	public $example = [
        'attributes' => [
            'preview_image' => 'responsive-image.png'
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
            'image_desktop' => get_field('image_desktop'),
            'image_mobile' => get_field('image_mobile')
        ];

        if($this->preview && (!$result['image_desktop'] || !$result['image_mobile'])){
            return [
                'image_desktop' => [
                    'url' => 'https://fakeimg.pl/500x300/?retina=1&text=Desktop%20Image',
                    'alt' => 'Desktop Image (Placeholder)'
                ],
                'image_mobile' => [
                    'url' => 'https://fakeimg.pl/500x300/?retina=1&text=Mobile%20Image',
                    'alt' => 'Mobile Image (Placeholder)'
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
        $responsiveImage = new FieldsBuilder('responsive_image');

        $responsiveImage
            ->addImage('image_desktop', [
                'label' => 'Image (Desktop)'
            ])
            ->addImage('image_mobile', [
                'label' => 'Image (Mobile)'
            ]);

        return $responsiveImage->build();
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
