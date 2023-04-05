<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class UpcomingOpportunities extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'sections.upcoming-opportunities'
	];

	/**
	 * Data to be passed to view before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		$blockData = [
			'side_label' => get_field('side_label', 'option'),
			'event_section' => get_field('event_section', 'option'),
			'program_application_section' => get_field('program_application_section', 'option'),
			'committee_openings_section' => get_field('committee_openings_section', 'option')
		];

		$data = array_merge($blockData, [
			'events' => \App\Providers\EventDataProvider::GetUpcomingEvents($blockData),
			'program_application_cards' => self::getCardsFormatData($blockData['program_application_section']['cards'] ?? []),
			'committee_openings_cards' => self::getCardsFormatData($blockData['committee_openings_section']['cards'] ?? []),
		]);

		return $data;
	}

	/**
	 * Get cards data.
	 *
	 * @return array
	 */
	public static function getCardsFormatData($cards = [])
	{
		$result = [];

		if(empty($cards)){
			$cards = [];
		}

		foreach ($cards as $card) {
			$result[] = [
				'style' => 'condensed-small',
				'title' => $card['title'],
				'post_type' => 'event',
				'permalink' => [
					'url' => self::getUrl($card['cta_type'], $card['post_id'], $card['external_url']),
					'target' => $card['new_tab'] ? '_blank' : '',
					'label' => ''
				],
				'formatted_date' => \App\Providers\CardsDataProvider::getStartAndEndDateString($card['start_date'], $card['end_date']),
				'formatted_time' => \App\Providers\CardsDataProvider::getStartAndEndTimeString($card['start_date'], $card['end_date'], $card['timezone_label'])
			];
		}

		return $result;
	}


	public static function getUrl($type, $postId, $externalUrl){
        $result = '';
        if($type === 'external_url'){
            $result = $externalUrl;
        }else{
            $result = get_the_permalink($postId);
        }

        return $result;
    }
}
