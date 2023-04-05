<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FrLayoutColumn extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Fr Layout Column';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-layout-column';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Fr Layout Column block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

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
    public $parent = ['acf/fr-page-builder-module-layout'];

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
        'anchor' => false,
        'mode' => false,
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
        return [
            'allowed_blocks' => json_encode([
                'acf/fr-page-builder-module-wysiwyg',
                'acf/fr-page-builder-module-tags-tooltips',
                'acf/fr-page-builder-module-cta',
                'acf/fr-page-builder-module-accordion',
                'acf/fr-page-builder-module-responsive-image',
                'acf/fr-page-builder-module-data-points',
                'acf/fr-page-builder-module-network-map',
                'acf/fr-page-builder-module-video-embed-caption',
                'acf/fr-page-builder-module-people-list'
            ])
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return [];
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
