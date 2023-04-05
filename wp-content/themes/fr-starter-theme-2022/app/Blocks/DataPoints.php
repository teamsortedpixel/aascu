<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class DataPoints extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Data Points';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Data Points block.';

    /**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-data-points';

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
        'DataPoints' => '<h1>$2.5 Lorem Ipsum </h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>',
        'attributes' => [
            'preview_image' => 'DataPoints.png'
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
   
        if($this->preview && empty(get_field('DataPoints'))){
            return $this->example;
        }

        return [
            'DataPoints' => get_field('DataPoints')? get_field('DataPoints'): false,
        ];
   
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $dataPoints = new FieldsBuilder('data_points');

        $dataPoints
            ->addWysiwyg('DataPoints',[
                'required' => 1,
                'default_value' => '<h1>$2.5 Lorem Ipsum</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>'
            ]);

        return $dataPoints->build();
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
