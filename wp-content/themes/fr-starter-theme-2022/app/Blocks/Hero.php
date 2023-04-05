<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\BackgroundDecorationFields;
use App\Fields\Partials\CtaButtonFields;


class Hero extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Default Page Hero block.';

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
    public $post_types = ['post', 'page'];

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
		'buttons' => [],
        'attributes' => [
            'preview_image' => 'hero.png'
        ],
	];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        //if the type is event-hero then the data of the post fields need to come from that, otherwise use the current post's data
        $post = get_field('type') == 'event' && get_field('featured_event') ? get_field('featured_event') : get_post(); 

        $data = array_merge([
            'type' => get_field('type'),
			'content' => get_field('content'),
			'hero_image' => get_field('image'),
            'image_caption' => get_field('image-caption'),
            'circles' => get_field('circles'),
			'buttons' => get_field('buttons') ?: [],
		], \App\Providers\CardsDataProvider::get($post));

        //special case for when hero == event index
        if($data['type'] == 'event' && get_field('featured_event')){
            $data['content'] = \App\Blocks\EventHero::generateEventContentField(wpautop($data['hero_content']), $data);
            $data['hero_image'] = $data['image'];
        }

        //append CTA button below Content field for Type primary & secondary
        $data['content'] = $this->generateContentField(    
            $data['type'], 
            $data['content'], 
            get_field('enable_cta_button'),
            get_field('cta_button')
        );

        if($data['type'] == 'event'){
            if($this->preview && !get_field('featured_event')){
                return $this->example;
            }else{
                return $data;
            }
        }else{
            if($this->preview && !$data['content'] && !$data['hero_image']){
                return $this->example;
            }else{
                return $data;
            }
        }

    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $hero = new FieldsBuilder('hero');

        //hero choices depend on the post type the block is being used on
        $hero_options = [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'front' => 'Home',
            'event' => 'Event Index'
        ];

		$hero
            ->addRadio('type', [
                'layout' => 'horizontal',
                'choices' => $hero_options,
                'default' => array_keys($hero_options)[0],
                'wrapper' => [
                    'class' => 'fr-event-type-selector'
                ]
            ])
			->addWysiwyg('content', [
				'wrapper' => [
					'width' => 80
				]
			])
                ->conditional('type', '!=', 'event')
			->addImage('image', [
				'wrapper' => [
					'width' => 20
				]
			])
                ->conditional('type', '!=', 'event')
            ->addText('image-caption', [
                'label' => 'Image Caption'
            ])
                ->conditional('type', '!=', 'event')
            ->addtruefalse('enable_cta_button', [
                'label' => 'Enable CTA Button?'
            ])
                ->conditional('type', '!=', 'event')
                    ->and('type', '!=', 'front')
            ->addGroup('cta_button', [
                'label' => 'CTA Button'
            ]) 
                ->conditional('type', '!=', 'event')
                    ->and('type', '!=', 'front')
                    ->and('enable_cta_button', '==', '1')
                ->addFields($this->get(CtaButtonFields::class))
            ->endGroup()
            ->addRepeater('buttons', [
                'layout' => 'block',
                'max' => 3
            ])
                ->conditional('type', '==', 'front')
                ->and('type', '!=', 'event')
                ->addFields($this->get(CtaButtonFields::class))
			->endRepeater()
            ->addPostObject('featured_event', [
                'label' => 'Featured Event',
                'post_type' => ['event']
            ])
                ->conditional('type', '==', 'event')
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

    /**
     * Pastes together the "content" field with the CTA button field that appears below
     * (if type is primary or secondary)
     *
     * @return void
     */
    public function generateContentField($type, $content, $enable_cta, $cta){
        $result = $content;

        if(in_array($type, ['primary', 'secondary']) && $enable_cta && isset($cta) && is_array($cta)){
            $cta = new \App\View\Components\CtaButton(
                $cta['label'] ?? false,
                $cta['cta_type'] ?? false,
                $cta['post_id'] ?? false,
                $cta['style'] ?? false,
                true,
                $cta['externa_url'] ?? false,
                $cta['anchor'] ?? false,
                $cta['new_tab'] ?? false,
                false,
                $this->preview
            );
            $cta = $cta->render()->with($cta->data());
            
            $result .= $cta;
        }

        return $result;
    }
}
