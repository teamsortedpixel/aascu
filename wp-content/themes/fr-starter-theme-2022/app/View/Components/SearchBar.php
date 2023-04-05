<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchBar extends Component
{

    public $default_value;
    public $placeholder_text;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->default_value = isset($_GET) && isset($_GET['s']) ? $_GET['s'] : false;
        $this->placeholder_text = 'Search...';
        $this->id = uniqid('search-bar-');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-bar');
    }
}
