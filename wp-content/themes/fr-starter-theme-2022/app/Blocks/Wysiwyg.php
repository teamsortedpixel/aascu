<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Wysiwyg extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Wysiwyg Module';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Wysiwyg Module block.';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-wysiwyg';

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
    public $parent = [
        'acf/block-container',
        'acf/fr-layout-column'
    ];

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
        'content' => '<p>Start editing content <strong>here.</strong></p>',
        'attributes' => [
            'preview_image' => 'wysiwyg.png'
        ],
	];

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return $this->items();
	}

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $wysiwyg = new FieldsBuilder('fr_page_builder_module_wysiwyg');

        $wysiwyg
            ->addWysiwyg('content');

        return $wysiwyg->build();
    }

	/**
	 * Return the items field.
	 *
	 * @return array
	 */
	public function items()
	{
		return [
            'content' => $this->preview && !get_field('content') ? $this->example['content'] : get_field('content')
		];
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
