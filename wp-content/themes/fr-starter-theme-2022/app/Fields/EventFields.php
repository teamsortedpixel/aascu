<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class EventFields extends Field
{
	/**
	 * The field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$eventFields = new FieldsBuilder('event_fields');

		$eventFields
			->setLocation('post_type', '==', 'event');

		$eventFields
			->addDateTimePicker('start_date', [
				'return_format' => 'Y-m-d H:i:s',
				'required' => 1,
				'wrapper' => [
					'width' => 33
				]
			])
			->addDateTimePicker('end_date', [
				'return_format' => 'Y-m-d H:i:s',
				'required' => 1,
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
			->addTrueFalse('hide_time', [
				'label' => 'Hide time from Hero area?',
				'wrapper' => [
					'width' => 15
				]
			])
			->addText('location',[
				'wrapper' => [
					'width' => 85
				]
			])
			->addText('cta_label', [
				'label' => 'Card CTA Label',
				'default_value' => \App\Providers\CardsDataProvider::EVENT_CTA_LABEL_DEFAULT,
			]);

		return $eventFields->build();
	}
}
