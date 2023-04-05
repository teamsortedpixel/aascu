<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PostTemplatesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        add_action('init', '\\App\Providers\PostTemplatesProvider::SetTemplates', 20);
    }

    public static function SetTemplates(){
        self::SetPageTemplate();
        self::SetEventTemplate();
        self::SetNewsResourceTemplate();
        self::SetPostTemplate();
    }

    /** TODO */
    public static function SetPageTemplate(){
        $page = get_post_type_object('page');

        $page->template = [
            ['acf/fr-page-builder', [], [
                ['acf/hero', [
                    'data' => [
                        'field_event_hero_type' => 'primary',
                        'field_hero_content' => '<h1>Regular Page Hero</h1><p>Start Editing Here</p>',
                        'field_event_hero_circles' => [
                            '63c5af63b2b87' => [
                                'field_event_hero_circles_style' => 'filled',
                                'field_event_hero_circles_size' => '319', 
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '12',
                                        'left' => '-250',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '-235',
                                        'left' => '-220',
                                        'unit' => 'px'
                                    ]
                                ]
                            ],
                            '63c5afe0b2b88' => [
                                'field_event_hero_circles_style' => 'outline',
                                'field_event_hero_circles_size' => '249',
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '',
                                        'right' => '-125',
                                        'bottom' => '-135',
                                        'left' => '',
                                        'unit' => 'px',
                                        'linked' =>'0',
                                    ],
                                    'mobile' => [
                                        'top' => '-135',
                                        'right' => '-125',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ] 
                            ]
                        ]
                    ]
                ]],
                ['acf/block-container', [], [
                    ['acf/fr-page-builder-module-wysiwyg']
                ]]
            ]]
        ];
    }

    public static function SetEventTemplate(){
        $event = get_post_type_object('event');

        $event->template = [
            ['acf/fr-page-builder', [], [
                ['acf/event-hero', [
                    'data' => [
                        'field_event_hero_type' => 'event',
                        'field_event_hero_content' => '<h1>Event Hero Content</h1><p>Start Editing Here</p>',
                        'field_event_hero_circles' => [
                            '63c5af63b2b87' => [
                                'field_event_hero_circles_style' => 'filled',
                                'field_event_hero_circles_size' => '319', 
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '12',
                                        'left' => '-250',
                                        'unit' => 'px'
                                    ],
                                    'tablet' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '-235',
                                        'left' => '-220',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ]
                            ],
                            '63c5afe0b2b88' => [
                                'field_event_hero_circles_style' => 'outline',
                                'field_event_hero_circles_size' => '249',
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '',
                                        'left' => '',
                                        'bottom' => '-62',
                                        'right' => '408',
                                        'unit' => 'px',
                                        'linked' =>'0',
                                    ],
                                    'tablet' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ] 
                            ]
                        ]
                    ]
                ]],
                ['acf/block-container', [], [
                    ['acf/fr-page-builder-module-wysiwyg']
                ]]
            ]]
        ];
    }

    public static function SetNewsResourceTemplate(){
        $news = get_post_type_object('news');
        $resource = get_post_type_object('resource');

        $template = [
            ['acf/fr-page-builder', [], [
                ['acf/news-resource-hero', [
                    'data' => [
                        'field_event_hero_type' => 'resource',
                        'field_event_hero_content' => '<h1>News/Resource Hero Content</h1><p>Start Editing Here</p>',
                        'field_event_hero_circles' => [
                            '63c5af63b2b87' => [
                                'field_event_hero_circles_style' => 'filled',
                                'field_event_hero_circles_size' => '319', 
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '12',
                                        'left' => '-250',
                                        'unit' => 'px'
                                    ],
                                    'tablet' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '-235',
                                        'left' => '-220',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ]
                            ],
                            '63c5afe0b2b88' => [
                                'field_event_hero_circles_style' => 'outline',
                                'field_event_hero_circles_size' => '249',
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '',
                                        'left' => '',
                                        'bottom' => '-62',
                                        'right' => '408',
                                        'unit' => 'px',
                                        'linked' =>'0',
                                    ],
                                    'tablet' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ] 
                            ]
                        ]
                    ]
                ]],
                ['acf/block-container', [], [
                    ['acf/fr-page-builder-module-wysiwyg']
                ]]
            ]]
        ];

        $news->template = $template;
        $resource->template = $template;
    }

    public static function SetPostTemplate(){
        $post = get_post_type_object('post');

        $post->template = [
            ['acf/fr-page-builder', [], [
                ['acf/hero', [
                    'data' => [
                        'field_event_hero_type' => 'primary',
                        'field_hero_content' => '<h1>Regular Post Hero</h1><p>Start Editing Here</p>',
                        'field_event_hero_circles' => [
                            '63c5af63b2b87' => [
                                'field_event_hero_circles_style' => 'filled',
                                'field_event_hero_circles_size' => '319', 
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '12',
                                        'left' => '-250',
                                        'unit' => 'px'
                                    ],
                                    'mobile' => [
                                        'top' => '',
                                        'right' => '',
                                        'bottom' => '-235',
                                        'left' => '-220',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ]
                            ],
                            '63c5afe0b2b88' => [
                                'field_event_hero_circles_style' => 'outline',
                                'field_event_hero_circles_size' => '249',
                                'field_event_hero_circles_position' => [
                                    'desktop' => [
                                        'top' => '',
                                        'right' => '-125',
                                        'bottom' => '-135',
                                        'left' => '',
                                        'unit' => 'px',
                                        'linked' =>'0',
                                    ],
                                    'mobile' => [
                                        'top' => '-135',
                                        'right' => '-125',
                                        'bottom' => '',
                                        'left' => '',
                                        'linked' =>'0',
                                        'unit' => 'px'
                                    ]
                                ] 
                            ]
                        ]
                    ]
                ]],
                ['acf/block-container', [], [
                    ['acf/fr-page-builder-module-wysiwyg']
                ]]
            ]]
        ];
    }
}
