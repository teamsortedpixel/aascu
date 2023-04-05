<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Providers\CardsDataProvider;

class Header extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'sections.header'
	];

	/**
	 * Data to be passed to view before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return [
			'logo' => get_field('header_logo', 'option'),
			'programs_submenu' => $this->getProgramsSubmenuData() 
		];
	}

	public function getProgramsSubmenuData(){
		$result = [];
		$data = get_field('programs_submenu', 'option');

		foreach ($data?: [] as $i) {
			$program_term = CardsDataProvider::getPrimaryTermByTaxonomy($i->ID, 'program');
			
			if($program_term){
				$result[] = [
					'title' => get_the_title($i),
					'permalink' => get_the_permalink($i),
					'description' => term_description($program_term->term_id),
					'accent_color' => get_field('accent_color', 'program_' . $program_term->term_id),
					'text_color' => get_field('text_color', 'program_' . $program_term->term_id),
				];
			}
		}

		return $result;
	}
}
