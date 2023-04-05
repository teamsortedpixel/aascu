<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Providers\PostSearchProvider;

class GeneralSearch extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'search'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'ajax_config' => wp_json_encode([
                'url' => admin_url('admin-ajax.php'),
                'action' => PostSearchProvider::GENERAL_SEARCH_ACTION,
                'posts_per_page' => 4,
                'page_number' => 0,
                'search' => isset($_GET) && isset($_GET['s']) ? $_GET['s'] : ''
            ])
        ];
    }
}
