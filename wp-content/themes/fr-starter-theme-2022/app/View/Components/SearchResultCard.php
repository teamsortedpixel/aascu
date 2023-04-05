<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Providers\CardsDataProvider;

class SearchResultCard extends Component
{

    public $title;
    public $excerpt;
    public $program_tags;
    public $image;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($post = false)
    {
        $data = false;

        if($post){
            $data = CardsDataProvider::get($post);
        }

        $this->title = $data ? $data['title'] : '';
        $this->excerpt = $data ? $data['excerpt'] : '';
        $this->program_tags = $data ? $data['program_tags'] : '';
        $this->image = $data && $data['image'] ? $data['image'] : false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-result-card');
    }
}
