<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FilterableContentList extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Filterable Content List';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder-module-filterable-content-list';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple filterable content list block.';

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
        'cards_data' => [
            [
                'tag' => 'United States',
                'image' => [
                    'url' => 'https://via.placeholder.com/500',
                    'alt' => 'Placeholder image'
                ],
                'title' => 'Test Card',
                'description' => 'This is just a test. Start editing content for this block.',
                'link' => [
                    'title' => 'Click me',
                    'url' => 'javascript:void(0)',
                    'target' => ''
                ]
            ]
        ],
        'source' => 'manual',
        'attributes' => [
            'preview_image' => 'filterable-content-list.png'
        ],
    ];

    

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        $blockData = [
			'post_type' => get_field('post_type'),
            'load_more_text' => get_field('load_more_text'),
            'order_by' => get_field('order_by'),
            'posts_per_page' => get_field('posts_per_page'),
            'show_filters_in_frontend' => get_field('show_filters_in_frontend'),
			'event_taxonomies' => get_field('event_taxonomies'),
			'news_taxonomies' => get_field('news_taxonomies'),
			'resource_taxonomies' => get_field('resource_taxonomies'),
			'program_taxonomies' => get_field('program_taxonomies'),
            'news_resource_taxonomies' => get_field('news_resource_taxonomies'),
            'taxonomy_filters' => get_field('taxonomy_filters'),
		];

        switch($blockData['post_type']){
            case 'event':
                $blockData['filter_taxonomies'] = $blockData['event_taxonomies']?:[];
                break;
            case 'news':
                $blockData['filter_taxonomies'] = $blockData['news_taxonomies']?:[];
                break; 
            case 'resource':
                $blockData['filter_taxonomies'] = $blockData['resource_taxonomies']?:[];
                break; 
            case 'page':
                $blockData['filter_taxonomies'] = $blockData['program_taxonomies']?:[];
                break;
            case 'news_resource':
                $blockData['filter_taxonomies'] = $blockData['news_resource_taxonomies']?:[];
                break;
            default:
                $blockData['filter_taxonomies'] = [];
        }

        $blockData['filter_taxonomies'] = \App\Providers\TaxonomyDataProvider::GetFilteredTermsByBlockInfo($blockData);

        $data = array_merge($blockData, [
            'ajax_config' => \App\Providers\FilterableContentProvider::GetAjaxConfig($blockData),
            'header_filters' => \App\Providers\FilterableContentProvider::GetHeaderFilters($blockData),
			'cards_data' => \App\Providers\FilterableContentProvider::GetPostsByType($blockData)   
		]);

        if($this->preview && count($data['cards_data']) == 0){
            return array_merge($data, $this->exampleData());
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
        $blockFields = new FieldsBuilder('filterable-content-list');

        $blockFields
            ->addSelect('post_type', [
                'choices' => [
                    'event' => 'Event',
                    'news' => 'News',
                    'resource' => 'Resource',
                    'news_resource' => 'News and Resources',
                    'page' => 'Program Page'
                ],
                'multiple' => 0,
                'allow_null' => 0,
                'default' => 'event',
                'wrapper' => [
					'width' => 50
				]
            ])
            ->addTrueFalse('show_filters_in_frontend', [
                'default_value' => 1,
                'wrapper' => [
                    'width' => 50
                ]
            ])            
            ->addText('load_more_text', [
                'wrapper' => [
                    'width' => 50
                ]
            ])
            ->addNumber('posts_per_page', [
                'default_value' => 9,
                'wrapper' => [
                    'width' => 25
                ]
            ])
            ->addSelect('order_by', [
                'choices' => [
                    'date' => 'Publish Date',
                    'title' => 'Title'
                ],
                'multiple' => 0,
                'allow_null' => 0,
                'default' => 'date',
                'wrapper' => [
					'width' => 25
				]
            ])
            ->addRepeater('event_taxonomies', [
                'label' => 'Filter Taxonomies',
                'layout' => 'block'
            ])
                ->conditional('post_type', '==', 'event')
                ->and('show_filters_in_frontend', '==', 1)
                ->addText('title', [
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addTrueFalse('show_custom_filters', [
                    'default_value' => 0,
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addSelect('taxonomy', [
                    'label' => 'Taxonomy',
                    'choices' => [
                        'format' => 'Event Format',
                        'program' => 'Program Area',
                        'role' => 'Role',
                        'post_tag' => 'Tags'
                    ],
                    'default' => 'format',
                    'multiple' => 0,
                    'allow_null' =>0,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 0)
                ->addField('custom_filters', 'multiple_taxonomy', [
                    'label' => 'Taxonomy',
                    'return_format' => 'object',
                    'taxonomy' => [
                        'program',
                        'format',
                        'role',
                        'post_tag'
                    ],
                    'ui' => 1,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 1)
            ->endRepeater()
            ->addRepeater('news_taxonomies',[
                'label' => 'Filter Taxonomies',
                'layout' => 'block'
            ])
                ->conditional('post_type', '==', 'news')
                ->and('show_filters_in_frontend', '==', 1)
                ->addText('title', [
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addTrueFalse('show_custom_filters', [
                    'default_value' => 0,
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addSelect('taxonomy', [
                    'label' => 'Taxonomy',
                    'choices' => [
                        'news-type' => 'News Type',
                        'program' => 'Program',
                        'post_tag' => 'Tags'
                    ],
                    'default' => 'news-type',
                    'multiple' => 0,
                    'allow_null' =>0,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 0)
                ->addField('custom_filters', 'multiple_taxonomy', [
                    'label' => 'Taxonomy',
                    'return_format' => 'object',
                    'taxonomy' => [
                        'news-type',
                        'program',
                        'post_tag'
                    ],
                    'ui' => 1,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 1)
            ->endRepeater()
            ->addRepeater('resource_taxonomies',[
                'label' => 'Filter Taxonomies',
                'layout' => 'block'
            ])
                ->conditional('post_type', '==', 'resource')
                ->and('show_filters_in_frontend', '==', 1)
                ->addText('title', [
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addTrueFalse('show_custom_filters', [
                    'default_value' => 0,
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addSelect('taxonomy', [
                    'label' => 'Taxonomy',
                    'choices' => [
                        'resource-type' => 'Resource Type',
                        'program' => 'Program',
                        'role' => 'Role',
                        'post_tag' => 'Tags'
                    ],
                    'default' => 'resource-type',
                    'multiple' => 0,
                    'allow_null' =>0,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 0)
                ->addField('custom_filters', 'multiple_taxonomy', [
                    'label' => 'Taxonomy',
                    'return_format' => 'object',
                    'taxonomy' => [
                        'resource-type',
                        'program',
                        'role',
                        'post_tag'
                    ],
                    'ui' => 1,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 1)
            ->endRepeater()
            ->addRepeater('program_taxonomies',[
                'label' => 'Filter Taxonomies',
                'layout' => 'block'
            ])
                ->conditional('post_type', '==', 'page')
                ->and('show_filters_in_frontend', '==', 1)
                ->addText('title', [
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addTrueFalse('show_custom_filters', [
                    'default_value' => 0,
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addSelect('taxonomy', [
                    'label' => 'Taxonomy',
                    'choices' => [
                        'program' => 'Program',
                        'role' => 'Role',
                        'post_tag' => 'Tag'
                    ],
                    'default' => 'program',
                    'multiple' => 0,
                    'allow_null' => 0,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 0)
                ->addField('custom_filters', 'multiple_taxonomy', [
                    'label' => 'Taxonomy',
                    'return_format' => 'object',
                    'taxonomy' => [
                        'program',
                        'role',
                        'post_tag'                     
                    ],
                    'ui' => 1,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 1)
            ->endRepeater()
            ->addRepeater('news_resource_taxonomies',[
                'label' => 'Filter Taxonomies',
                'layout' => 'block'
            ])
                ->conditional('post_type', '==', 'news_resource')
                ->and('show_filters_in_frontend', '==', 1)
                ->addText('title', [
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addTrueFalse('show_custom_filters', [
                    'default_value' => 0,
                    'wrapper' => [
                        'width' => 30
                    ]
                ])
                ->addSelect('taxonomy', [
                    'label' => 'Taxonomy',
                    'choices' => [
                        'news-type' => 'News Type',
                        'resource-type' => 'Resource Type',
                        'program' => 'Program',
                        'role' => 'Role',
                        'post_tag' => 'Tag'
                    ],
                    'default' => 'program',
                    'multiple' => 0,
                    'allow_null' => 0,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 0)
                ->addField('custom_filters', 'multiple_taxonomy', [
                    'label' => 'Taxonomy',
                    'return_format' => 'object',
                    'taxonomy' => [
                        'news-type',
                        'resource-type',
                        'program',
                        'role',
                        'post_tag'                     
                    ],
                    'ui' => 1,
                    'wrapper' => [
                        'width' => 40
                    ]
                ])
                ->conditional('show_custom_filters', '==', 1)
            ->endRepeater()
            ->addGroup('taxonomy_filters')
                ->conditional('show_filters_in_frontend', '==', 0)
                ->addTaxonomy('event_formats', [
                    'label' => 'Event Formats',
                    'taxonomy' => 'format',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                ->conditional('post_type', '==', 'event')
                ->addTaxonomy('news_types', [
                    'label' => 'News Types',
                    'taxonomy' => 'news-type',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                ->conditional('post_type', '==', 'news')
                    ->or('post_type', '==', 'news_resource')
                ->addTaxonomy('resource_types', [
                    'label' => 'Resource Types',
                    'taxonomy' => 'resource-type',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                ->conditional('post_type', '==', 'resource')
                    ->or('post_type', '==', 'news_resource')
                ->addTaxonomy('programs', [
                    'label' => 'Program Areas',
                    'taxonomy' => 'program',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                    ->conditional('post_type', '==', 'event')
                    ->or('post_type', '==', 'resource')
                    ->or('post_type', '==', 'news')
                    ->or('post_type', '==', 'page')
                    ->or('post_type', '==', 'news_resource')
                ->addTaxonomy('roles', [
                    'label' => 'Roles',
                    'taxonomy' => 'role',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                ->conditional('post_type', '==', 'event')
                    ->or('post_type', '==', 'resource')
                    ->or('post_type', '==', 'page')
                    ->or('post_type', '==', 'news_resource')
                ->addTaxonomy('tags', [
                    'label' => 'Post tags',
                    'taxonomy' => 'post_tag',
                    'field_type' => 'multi_select',
                    'add_term' => 0,
                    'save_terms' => 0,
                    'load_terms' => 0,
                    'multiple' => 1,
                    'return_format' => 'object',
                    'wrapper' => [
                        'width' => 50
                    ]
                ])
                    ->conditional('post_type', '==', 'event')
                    ->or('post_type', '==', 'resource')
                    ->or('post_type', '==', 'news')
                    ->or('post_type', '==', 'page')
                    ->or('post_type', '==', 'news_resource')
            ->endGroup();

        return $blockFields->build();
    }


    /**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function exampleData() {
		$result = [
			'cards_data' => [
                'cards' => [
                    view('components.card', [
                        'post_type' => 'news',
                        'style' => 'card-type-regular',
                        'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                        'excerpt' => '',
                        'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
                        'permalink' => [
                            'label' => 'Read more.',
                            'url' => 'javascript:void(0)',
                            'target' => false
                        ],
                        'image' => false,
                        'format' => false,
                        'program' => false,
                        'resource_type' => false,
                        'formatted_date' => false,
                        'formatted_time' => false,
                        'publication_date' => 'January 1, 2023',
                        'author' => false,
                    ])->render(),
                    view('components.card', [
                        'post_type' => 'resource',
                        'style' => 'card-type-regular',
                        'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'excerpt' => '',
                        'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
                        'permalink' => [
                            'label' => 'Read more.',
                            'url' => 'javascript:void(0)',
                            'target' => false
                        ],
                        'image' => false,
                        'format' => false,
                        'program' => false,
                        'resource_type' => false,
                        'formatted_date' => false,
                        'formatted_time' => false,
                        'publication_date' => false,
                        'author' => 'Author/<br>Institution Name ',
                    ])->render(),
                    view('components.card', [
                        'post_type' => 'news',
                        'style' => 'card-type-regular',
                        'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'excerpt' => '',
                        'program_tags' => '<span style="" class="badge program-badge">Example Program</span>',
                        'permalink' => [
                            'label' => 'Read more.',
                            'url' => 'javascript:void(0)',
                            'target' => false
                        ],
                        'image' => false,
                        'format' => false,
                        'program' => false,
                        'resource_type' => false,
                        'formatted_date' => false,
                        'formatted_time' => false,
                        'publication_date' => 'January 1, 2023',
                        'author' => false,
                    ])->render() 
                ]
			],
            'post_type' => 'news',
			'attributes' => [
                'preview_image' => 'filterable-content-list.png'
            ],
		];

		return $result;
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
