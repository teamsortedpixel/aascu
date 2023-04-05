<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SecondaryNavDropdownItem extends Component
{

    public $color;
    public $title;
    public $text_color;
    public $description;
    public $permalink;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($color = false, $title = false, $textColor = false, $description = '', $permalink = '')
    {
        $this->color = $color ?: '#EF3B48';
        $this->title = $title ?: '';
        $this->text_color = $textColor ?: '';
        $this->description = $description ?: '';
        $this->permalink = $permalink ?: '';
        $this->id = uniqid('sec-nav-');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.secondary-nav-dropdown-item');
    }
}
