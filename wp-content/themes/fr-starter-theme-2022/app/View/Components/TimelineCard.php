<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TimelineCard extends Component
{

    public $date;
    public $title;
    public $description;
    public $card_image;
    public $modal_image;
    public $modal_content;
    public $modal_id;
    public $index;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($date = '', $title = '', $description = '', $cardImage = false, $modalImage = false, $modalContent = '', $modalId = '', $index = 0)
    {
        $this->date = $date;
        $this->title = $title;
        $this->description = $description;
        $this->card_image = $cardImage;
        $this->modal_image = $modalImage;
        $this->modal_content = $modalContent;
        $this->modal_id = $modalId;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.timeline-card');
    }
}
