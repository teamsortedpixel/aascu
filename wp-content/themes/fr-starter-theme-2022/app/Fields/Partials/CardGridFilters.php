<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Partial;
use StoutLogic\AcfBuilder\FieldsBuilder;

class CardGridFilters extends Partial
{
	public $post_types = ['news', 'resource', 'event', 'page__program', 'news__resources'];

	/**
	 * The partial field group.
	 *
	 * @return \StoutLogic\AcfBuilder\FieldsBuilder
	 */
	public function fields()
	{
		$cardGridFilters = new FieldsBuilder('card_grid_filters');

		$post_types = [];
        foreach($this->post_types as $p){
			if($p == 'page__program'){
				$post_types[$p] = 'Program Pages';
			}else if($p == 'news__resources'){
				$post_types[$p] = 'News and Resources';
			}else{
				$post_types[$p] = ucwords($p);
			}
        }

		$cardGridFilters
			->addRadio('post_types', [
				'choices' => $post_types,
				'layout' => 'horizontal',
				'wrapper' => [
					'width' => 70
				]
			])
			->addNumber('card_count', [
				'min' => 3,
				'default_value' => 3,
				'wrapper' => [
					'width' => 30
				]
			])
			->addGroup('taxonomies', [
				'layout' => 'block'
			])
				->addTaxonomy('program', [
					'taxonomy' => 'program',
					'field_type' => 'checkbox',
					'multiple' => 1,
					'add_term' => 0,
					'wrapper' => [
						'width' => 50
					]
				])
					->conditional('post_types', '!=', '')
				->addTaxonomy('post_tag', [
					'label' => 'Tag',
					'taxonomy' => 'post_tag',
					'field_type' => 'checkbox',
					'multiple' => 1,
					'add_term' => 0,
					'wrapper' => [
						'width' => 50
					]
				])
					->conditional('post_types', '==', 'news')
					->or('post_types', '==', 'event')
					->or('post_types', '==', 'resource')
					->or('post_types', '==', 'news__resources')
				->addTaxonomy('news-type', [
					'label' => 'News Type',
					'taxonomy' => 'news-type',
					'field_type' => 'checkbox',
					'multiple' => 1,
					'add_term' => 0,
					'wrapper' => [
						'width' => 50
					]
				])
					->conditional('post_types', '==', 'news')
					->or('post_types', '==', 'news__resources')
				->addRadio('event_status', [
					'choices' => [
						'any' => 'Any',
						'past' => 'Past',
						'current' => 'Current'
					],
					'default_value' => 'any',
					'layout' => 'horizontal',
					'wrapper' => [
						'width' => 50
					]
				])
					->conditional('post_types', '==', 'event')
				->addTaxonomy('format', [
					'taxonomy' => 'format',
					'field_type' => 'checkbox',
					'multiple' => 1,
					'add_term' => 0,
					'wrapper' => [
						'width' => 50
					]
				])
					->conditional('post_types', '==', 'event')
					->addTaxonomy('resource-type', [
						'label' => 'Resource Type',
						'taxonomy' => 'resource-type',
						'field_type' => 'checkbox',
						'multiple' => 1,
						'add_term' => 0,
						'wrapper' => [
							'width' => 50
						]
					])
						->conditional('post_types', '==', 'resource')
						->or('post_types', '==', 'news__resources')
					->addTaxonomy('role', [
						'taxonomy' => 'role',
						'field_type' => 'checkbox',
						'multiple' => 1,
						'add_term' => 0,
						'wrapper' => [
							'width' => 50
						]
					])
						->conditional('post_types', '==', 'resource')
						->or('post_types', '==', 'event')
						->or('post_types', '==', 'news__resources')
			->endGroup();

		return $cardGridFilters;
	}
}
