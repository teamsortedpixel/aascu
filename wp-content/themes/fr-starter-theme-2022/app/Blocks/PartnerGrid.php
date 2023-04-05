<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class PartnerGrid extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Partner Grid';

     /**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-partner-grid';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Partner Grid block.';

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
        'style' => 'style-1',
        'logo_grid' => [
            [
                'acf_fc_layout' => 'partner_grid_logo_group_layout',
                'title' => 'Example Title 1',
                'logos' => [
                    [
                        'logo' => [
                            'url' =>'https://picsum.photos/200/110',
                        ],
                        'link' => false,
                    ],
                    [
                        'logo' => [
                            'url' =>'https://picsum.photos/200/110',
                        ],
                        'link' => false,
                    ],
                    [
                        'logo' => [
                            'url' =>'https://picsum.photos/200/110',
                        ],
                        'link' => false,
                    ]
                ]
            ]
        ],
        'attributes' => [
            'preview_image' => 'LogoGrid.png'
        ]
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        $blockData = [
            'style' => get_field('partner_grid_style'),
            'logo_grid' => get_field('logo_grid')?: []
		];

		if($this->preview && empty($blockData['logo_grid'])){
            return $this->example;
		}
		
		return $blockData;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {

        $partnerGrid = new FieldsBuilder('partner_grid');

        $partnerGrid
            ->addRadio('partner_grid_style', [
                'label' => 'Style',
                'choices' => [
                    'style-1' => 'Style 1',
                    'style-2' => 'Style 2'
                ],
                'default_value' => 'style-1',
                'layout' => 'horizontal' 
            ])
            ->addFlexibleContent('logo_grid', [
                'label' => 'Logo Grid',
                'button_label' => 'Add Logo Group'
            ])
                ->addLayout($this->getLogoGroupLayout())
            ->endFlexibleContent();

        return $partnerGrid->build();
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

    public function getLogoGroupLayout(){
        /**
         * Logo Group Layout 
         */
        $logoGroupLayout = new FieldsBuilder('partner_grid_logo_group_layout', [
            'title' => 'Logo Group'
        ]);
        $logoGroupLayout
            ->addText('title')
            ->addRepeater('logos', [
                'label' => 'Logos',
                'layout' => 'block',
                'collapsed' => 'logo',
            ])
                ->addImage('logo', [
                    'wrapper' => [
                        'width' => 25
                    ],
                    'required' => 1
                ])
                ->addLink('link', [
                    'wrapper' => [
                        'width' => 75
                    ]
                ])
                ->addWysiwyg('description', [
                    'media_upload' => 0
                ])
                    ->conditional('partner_grid_style', '==', 'style-2')
            ->endRepeater();

        return $logoGroupLayout;
    }
}

