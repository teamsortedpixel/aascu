<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'primaryNavigation' => $this->primaryNavigation(),
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    public function primaryNavigation() {
        $args = [
            'theme_location' => 'primary_navigation',
            'container'  => '',
            'container_class' => '',
            'menu_class' => 'navbar-nav mb-2 mb-lg-0',
            'depth' => 4,
            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
            'walker' => new \App\wp_bootstrap5_navwalker()
        ];

        return $args;
    }
}
