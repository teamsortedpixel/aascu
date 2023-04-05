<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectCard extends Component
{

    public $title;
    public $description;
    public $preview;
    public $image_url;
    public $image_alt;
    public $image_id;
    public $url;
    public $url_target;
    public $tag;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($preview = false, $title = '', $description = '', $image = false, $link = false, $tag = false)
    {
        $this->preview = $preview;
        $this->title = $title;
        $this->description = $description;
        $this->image_id = false;
        if($image && isset($image['id'])){
            $this->image_id = $image['id'];
        }else{
            $this->image_url = $image ? $image['url'] : false;
            $this->image_alt = $image ? $image['alt'] : false;
        }
        $this->url = $link && is_array($link) ? $link['url'] : false;
        $this->url_target =  $link && is_array($link) ? $link['target'] : false;
        $this->tag = $tag ? $tag : false;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.project-card');
    }
}
