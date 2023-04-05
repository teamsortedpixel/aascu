<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SideNavQuickLinks extends Component
{

    public $is_enabled;
    public $anchors = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->is_enabled = get_field('enable_side_nav') ?? false;

        if($this->is_enabled){
            $this->anchors = get_field('side_nav')['anchors'] ?? [];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.side-nav-quick-links');
    }
}
