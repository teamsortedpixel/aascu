<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Providers\CardsDataProvider;

class Card extends Component
{

	public $style;
	public $classes;
	public $post_type;
	public $title;
	public $excerpt;
	public $program_tags;
	public $program;
	public $permalink;
	public $image;
	public $format;
	public $resource_type;
	public $formatted_date;
	public $formatted_time;
	public $publication_date;
	public $hide_date;
	public $author;

	/**
	 * If the post object/ID is passed then use that to generate the data
	 * from the CardsDataProvider, or, pass the $card_data array if you want to 
	 * pass the information manually.
	 *
	 * @param int|object $post
	 * @param array $card_data
	 */
	public function __construct($post = false, $cardData = false, $style = false, $classes = false)
	{
		$data = $cardData;

		if($post){
			$data = CardsDataProvider::get($post);
		}

		$this->post_type = $data['post_type'] ?? ''; 
		$this->style = isset($style) && $style && $this->post_type !== 'page' ? $style : 'card-type-regular'; 
		$this->classes = $classes ?? false; 
		$this->title = $data['title'] ?? '';
		$this->excerpt = $data['excerpt'] ?? ''; 
		$this->program_tags = $data['program_tags'] ?? ''; 
		$this->permalink = $data['permalink'] ?? false; 
		$this->image = $data['image'] ?? false; 
		$this->format = $data['format'] ?? false; 
		$this->program = $data['program'] ?? false;
		$this->resource_type = $data['resource_type'] ?? false; 
		$this->formatted_date = $data['formatted_date'] ?? ''; 
		$this->formatted_time = $data['formatted_time'] ?? ''; 
		$this->publication_date = $data['publication_date'] ?? '';
		$this->hide_date = $data['hide_date'] ?? false;
		$this->author = $data['author'] ?? false; 
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render() {
		if($this->style == 'condensed-small' && $this->post_type == 'event'){
			return view('components.card-condensed-small');
		}

		return view('components.card');
	}

	public function foo(){
		return '<p>test</p>';
	}
}
