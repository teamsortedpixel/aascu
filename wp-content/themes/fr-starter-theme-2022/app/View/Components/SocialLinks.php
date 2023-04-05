<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SocialLinks extends Component
{

    public $social_links;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->social_links = $data ?: [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.social-links');
    }
}
