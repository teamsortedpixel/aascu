<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\SocialLinks;

class ThemeSettings extends Field
{
	/**
	 * The field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$themeSettings = new FieldsBuilder('theme_settings');

		$themeSettings
			->setLocation('options_page', '==', 'theme-settings')
			->addTab('Header Settings')
				->addImage('header_logo', [
					'wrapper' => [
						'width' => 30
					]
				])
				->addPostObject('programs_submenu', [
					'instructions' => 'Select the `Program` pages you want to show, the colors and description would be taken from the `Program` taxonomy associated to the `Program` page',
					'allow_null' => 1,
					'multiple' => 1,
					'post_type' => [
						'page'
					],
					'wrapper' => [
						'width' => 70,
						'class' => 'fr-filter-programs'
					]
				])
				->addTextarea('google_tag_manager_code_snippet', [
					'rows' => 3
				])
				->addTextarea('additional_google_tag_manager_code_snippet', [
					'rows' => 2
				])
			->addTab('Footer Settings')
				->addImage('footer_logo', [
					'label' => 'Logo',
				])
				->addText('footer_cta_heading', [
					'wrapper' => [
						'width' => '70'
					]
				])
				->addLink('footer_cta', [
					'wrapper' => [
						'width' => 30
					],
				])
				->addFields($this->get(SocialLinks::class))
				->addText('footer_copyright_text', [
					'wrapper' => [
						'width' => '50'
					],
					'prepend' => '© < YEAR >',
				])
			->addTab('General Settings')
				->addPostObject('program_pages_index_page', [
					'post_type' => [
						'page'
					]
				])
				->addPostObject('news_index_page', [
					'post_type' => [
						'page'
					]
				])
				->addPostObject('resources_index_page', [
					'post_type' => [
						'page'
					]
				])
				->addPostObject('events_index_page', [
					'post_type' => [
						'page'
					]
				])
				->addGroup('recaptcha_settings')
					->addMessage('Message', 'Recaptcha keys for validation on the Profile pages')
					->addText('recaptcha_site_key', [
						'label' => 'Site Key',
						'wrapper' => [
							'width' => 50
						]
					])
					->addText('recaptcha_secret_key', [
						'label' => 'Secret Key',
						'wrapper' => [
							'width' => 50
						]
					])
				->endGroup()
			->addTab('API Settings')
				->addTruefalse('enable_api', [
					'label' => 'Enable API?',
					'default_value' => 1,
					'instructions' => 'For development: Enable/Disable the API for better debugging.'
				])
				->addText('api_base_url', [
					'label' => 'Base API URL',
					'wrapper' => [
						'width' => 33
					]
				])
				->addText('api_username', [
					'label' => 'API Username',
					'wrapper' => [
						'width' => 33
					]
				])
				->addPassword('api_password', [
					'label' => 'API Password',
					'wrapper' => [
						'width' => 34
					]
				])
				->addText('aascu_clear_api_cache', [
					'label' => 'Clear all API caches',
					'wrapper' => [
						'class' => 'api-api-clear-caches'
					]
				])
			->addTab('Upcoming opportunities')
				->addText('side_label')
				->addGroup('event_section')
					->addText('title')
					->addRadio('source', [
						'choices' => [
							'manual' => 'Manually selected events',
							'upcoming' => 'Upcoming events'
						],
						'layout' => 'horizontal',
						'default_value' => 'manual'
					])
					->addPostObject('manual_events', [
						'return_format' => 'id',
						'multiple' => 1,
						'post_type' => [
							'event'
						]
					])
					->conditional('source', '==', 'manual')
					->addGroup('taxonomy_filters')
						->conditional('source', '==', 'upcoming')
/* 						->addTaxonomy('event-type', [
							'label' => 'Event Type',
							'taxonomy' => 'event-type',
							'field_type' => 'multi_select',
							'add_term' => 0,
							'save_terms' => 0,
							'load_terms' => 0,
							'multiple' => 1,
							'return_format' => 'id',
							'wrapper' => [
								'width' => 50
							]
						]) */
						->addTaxonomy('format', [
							'label' => 'Event Format',
							'taxonomy' => 'format',
							'field_type' => 'multi_select',
							'add_term' => 0,
							'save_terms' => 0,
							'load_terms' => 0,
							'multiple' => 1,
							'return_format' => 'id',
							'wrapper' => [
								'width' => 50
							]
						])
						->addTaxonomy('program', [
							'label' => 'Program',
							'taxonomy' => 'program',
							'field_type' => 'multi_select',
							'add_term' => 0,
							'save_terms' => 0,
							'load_terms' => 0,
							'multiple' => 1,
							'return_format' => 'id',
							'wrapper' => [
								'width' => 50
							]
						])
						->addTaxonomy('role', [
							'label' => 'Role',
							'taxonomy' => 'role',
							'field_type' => 'multi_select',
							'add_term' => 0,
							'save_terms' => 0,
							'load_terms' => 0,
							'multiple' => 1,
							'return_format' => 'id',
							'wrapper' => [
								'width' => 50
							]
						])
					->endGroup()
				->endGroup()
				->addGroup('program_application_section')
					->addText('title')
					->addRepeater('cards',[
						'label' => 'Cards',
						'max' => 6,
						'layout' => 'block'
					])
						->addText('title', [
							'wrapper' => [
								'width' => 100
							],
							'required' => 1
						])
						->addDateTimePicker('start_date', [
							'return_format' => 'Y-m-d H:i:s',
							'required' => 1,
							'wrapper' => [
								'width' => 33
							]
						])
						->addDateTimePicker('end_date', [
							'return_format' => 'Y-m-d H:i:s',
							'required' => 0,
							'wrapper' => [
								'class' => 'fr-validate-end-date',
								'width' => 33
							]
						])
						->addText('timezone_label', [
							'wrapper' => [
								'width' => 34
							]
						])
						->addRadio('cta_type', [
							'label' => 'CTA Type',
							'choices' => [
								'internal_url' => 'Internal Post/Page',
								'external_url' => 'External URL'
							],
							'wpml_cf_preferences' => 0,
							'wrapper' => [
								'width' => 20
							],
							'required' => 1
						])
						->addText('external_url', [
							'label' => 'External URL',
							'wrapper' => [
								'width' => 60
							],
							'required' => 1
						])
							->conditional('cta_type', '==', 'external_url')
						->addPostObject('post_id', [
							'label' => 'Internal Post/Page',
							'post_type' => [
								'page',
								'post',
								'event',
								'news',
								'resource',
								'people'
							],
							'wrapper' => [
								'width' => 60
							],
							'return_format' => 'id',
							'required' => 1
						])
							->conditional('cta_type', '==', 'internal_url')
						->addTrueFalse('new_tab', [
							'label' => 'Open in new tab?',
							'wrapper' => [
								'width' => 20
							]
						])
					->endRepeater()
				->endGroup()
				->addGroup('committee_openings_section')
					->addText('title')
					->addRepeater('cards',[
						'label' => 'Cards',
						'max' => 4,
						'layout' => 'block'
					])
						->addText('title', [
							'wrapper' => [
								'width' => 100
							],
							'required' => 1
						])
						->addDateTimePicker('start_date', [
							'return_format' => 'Y-m-d H:i:s',
							'required' => 1,
							'wrapper' => [
								'width' => 33
							]
						])
						->addDateTimePicker('end_date', [
							'return_format' => 'Y-m-d H:i:s',
							'required' => 0,
							'wrapper' => [
								'class' => 'fr-validate-end-date',
								'width' => 33
							]
						])
						->addText('timezone_label', [
							'wrapper' => [
								'width' => 34
							]
						])
						->addRadio('cta_type', [
							'label' => 'CTA Type',
							'choices' => [
								'internal_url' => 'Internal Post/Page',
								'external_url' => 'External URL'
							],
							'wpml_cf_preferences' => 0,
							'wrapper' => [
								'width' => 20
							],
							'required' => 1
						])
						->addText('external_url', [
							'label' => 'External URL',
							'wrapper' => [
								'width' => 60
							],
							'required' => 1
						])
							->conditional('cta_type', '==', 'external_url')
						->addPostObject('post_id', [
							'label' => 'Internal Post/Page',
							'post_type' => [
								'page',
								'post',
								'event',
								'news',
								'resource',
								'people'
							],
							'wrapper' => [
								'width' => 60
							],
							'return_format' => 'id',
							'required' => 1
						])
							->conditional('cta_type', '==', 'internal_url')
						->addTrueFalse('new_tab', [
							'label' => 'Open in new tab?',
							'wrapper' => [
								'width' => 20
							]
						])
					->endRepeater()
				->endGroup();

		return $themeSettings->build();
	}
}
