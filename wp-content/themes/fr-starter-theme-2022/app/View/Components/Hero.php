<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Hero extends Component
{
    public $content;
    public $blockData;
    public $program = false;
    public $program_tags = false;
    public $format = false;
    public $startDate = false;
    public $endDate = false;
    public $timezoneLabel = false;
    public $publicationDate = false;
    public $hideDate = false;
    public $formattedDate = false;
    public $formattedTime = false;
    public $author = false;
    public $image;
    public $imageCaption;
    public $buttons;
    public $type;
    public $circles;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($blockData = false, $content = false, $program = false, $format = false, $startDate = false, $endDate = false, $timezoneLabel = false, $publicationDate = false, $hideDate = false, $author = false, $image = false, $imageCaption = false, $buttons = [], $type = false, $circles = false, $formattedDate = false, $formattedTime = false, $programTags = false)
    {
        $this->blockData = $blockData;
        $this->type = $type;
        $this->content = $content;
        $this->program = $program;
        $this->program_tags = $programTags;
        $this->format = $format;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->timezoneLabel = $timezoneLabel;
        $this->publicationDate = $publicationDate;
        $this->hideDate = $hideDate;
        $this->author = $author;
        $this->image = $image;
        $this->imageCaption = $imageCaption;
        $this->buttons = $buttons;
        $this->circles = $circles?: [];
        $this->formattedDate = $formattedDate;
        $this->formattedTime = $formattedTime;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hero');
    }
}
