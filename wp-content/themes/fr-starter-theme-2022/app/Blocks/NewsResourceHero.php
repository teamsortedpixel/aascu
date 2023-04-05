<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\BackgroundDecorationFields;
use App\Fields\Partials\CtaButtonFields;

class NewsResourceHero extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'News Resource Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple News Resource Hero block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'fr-page-builder-blocks';

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
    public $post_types = ['news', 'resource'];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = ['acf/fr-page-builder'];

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
		'content' => '<h3>Lorem ipsum dolor sit amet!</h3><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium.</p>',
		'hero_image' => [
			'url' => 'https://picsum.photos/seed/picsum/516/416',
			'alt' => 'Example Image'
		],
        'circles' => [],
        'image_caption' =>'Lorem ipsum dolor sit amet',
        'attributes' => [
            'preview_image' => 'news-resource-hero.png'
        ],
	];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        $data = array_merge([
            'type' => get_field('type'),
			'content' => get_field('content'),
			'hero_image' => get_field('image'),
            'image_caption' => get_field('image-caption'),
            'circles' => get_field('circles')
		], \App\Providers\CardsDataProvider::get(get_post()));

		if($this->preview && !$data['content'] && !$data['hero_image']){
			return $this->example;
		}
        
        return $data;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $hero = new FieldsBuilder('news_resource_hero');

        //hero choices depend on the post type the block is being used on
        $hero_options = [
            'resource' => 'News/Resource'
        ];

		$hero
            ->addRadio('type', [
                'layout' => 'horizontal',
                'choices' => $hero_options,
                'default' => array_keys($hero_options)[0],
            ])
			->addWysiwyg('content', [
				'wrapper' => [
					'width' => 80
				]
			])
			->addImage('image', [
				'wrapper' => [
					'width' => 20
				]
			])
            ->addText('image-caption', [
                'label' => 'Image Caption'
            ])
            ->addFields($this->get(BackgroundDecorationFields::class));

		return $hero->build();
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
