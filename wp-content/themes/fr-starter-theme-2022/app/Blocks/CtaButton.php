<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\CtaButtonFields;

class CtaButton extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Cta Button';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Cta Button block.';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-cta';

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

    public $example = [
        'label' => 'Example CTA!',
        'type' => 'external_url',
        'external_url' => 'https://freerange.com',
        'post_id' => '',
        'anchor' => '',
        'style' => '',
        'new_tab' => false,
        'alignment' => 'left',
        'attributes' => [
            'preview_image' => 'cta-button.png'
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        if($this->preview && (!$this->items()['label'] && !$this->items()['external_url']  && !$this->items()['anchor'] && !$this->items()['post_id'])){
            return $this->example;
        }else{
            return $this->items();
        }
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $style_choices = [];

        foreach(\App\View\Components\CtaButton::getStyles() as $style){
            $style_choices[$style] = ucwords(str_replace('-', ' ', $style));
        }

        $ctaButton = new FieldsBuilder('cta_button');

        $ctaButton
            ->addRadio('alignment', [
                'choices' => [
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right'
                ],
                'wpml_cf_preferences' => 0,
                'default_value' => 'left',
                'layout' => 'horizontal',
                'required' => 1
            ])
            ->addFields($this->get(CtaButtonFields::class));

        return $ctaButton->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {

        return [
            'alignment' => get_field('alignment'),
            'label' => get_field('label'),
            'style' => get_field('style'),
            'type' => get_field('cta_type'),
            'external_url' => get_field('externa_url'),
            'post_id' => get_field('post_id'),
            'anchor' => get_field('anchor'),
            'new_tab' => get_field('new_tab'),
            'open_modal' => get_field('open_modal')
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
