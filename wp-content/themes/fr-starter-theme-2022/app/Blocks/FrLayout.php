<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FrLayout extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Free Range Columns';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Free Range Columns block.';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-layout';

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
        $allowed_blocks = [
            'acf/fr-layout-column'
        ];

        return [
            'layouts' => $this->items()['layouts'],
            'choices' => $this->items()['choices'],
            'max_width' => $this->getMaxWidthAttr(),
            'allowed_blocks' => json_encode($allowed_blocks)
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $frLayout = new FieldsBuilder('fr_layout');

        $frLayout
            ->addButtonGroup('layouts', [
                'choices' => $this->choices(),
                'allow_null' => 0,
                'wpml_cf_preferences' => 0,
                'wrapper' => [
                    'class' => 'fr-hide-first-opt'
                ]
            ])
            ->addRange('max_width', [
                'label' => 'Layout\'s Max Width',
                'min' => 10,
                'max' => 100,
                'append' => '%',
                'default_value' => 100
            ]);

        return $frLayout->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return [
            'layouts' => get_field('layouts'),
            'choices' => $this->choices(['remove_first' => true]),
        ];
    }

    public function choices($args = []) {
        $choices = [];
        $choices['-1'] = 'None';
        $choices['1_1'] = '1/1';
        $choices['12_12'] = '1/2 1/2';
        $choices['13_13_13'] = '1/3 1/3 1/3';
        $choices['16_46_16'] = '1/6 4/6 1/6';
        $choices['23_13'] = '2/3 1/3';
        $choices['13_23'] = '1/3 2/3';
        
        if(isset($args['remove_first']) && $args['remove_first']){
            unset($choices['-1']);
        }

        return $choices;
    }

    public function getMaxWidthAttr(){
        $max_width = get_field('max_width');
		return $max_width && $max_width !== 100 ? 'style=\'max-width:'.$max_width.'%;\'' : '' ;
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
