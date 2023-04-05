<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Partial;
use StoutLogic\AcfBuilder\FieldsBuilder;

class BackgroundDecorationFields extends Partial
{
	/**
	 * The partial field group.
	 *
	 * @return \StoutLogic\AcfBuilder\FieldsBuilder
	 */
	public function fields()
	{
		$backgroundDecorationFields = new FieldsBuilder('background_decoration_fields');

		$backgroundDecorationFields
			->addAccordion('background_decoration')
				->addTab('standard')
					->addRadio('circle_1', [
						'choices' => [
							'none' => 'None',
							'top-solid' => 'Top Solid',
							'left-solid' => 'Left Solid',
						],
						'default_value' => 'none',
						'layout' => 'horizontal'
					])
					->addRadio('circle_2', [
						'choices' => [
							'none' => 'None',
							'bottom-outline' => 'Bottom Outline',
							'right-outline' => 'Right Outline',
						],
						'default_value' => 'none',
						'layout' => 'horizontal'
					])
				->addTab('advanced')
					->conditional('circle_1', '==', 'none')
						->and('circle_2', '==', 'none')
					->addRepeater('circles', [
						'layout' => 'block',
						'button_label' => 'Add Circle'
					])
						->addRadio('style', [
							'label' => 'Circle\'s Style',
							'layout' => 'horizontal',
							'choices' => [
								'filled' => 'Filled',
								'outline' => 'Outline'
							],
							'default_value' => 'filled'
						])
						->addNumber('size', [
							'label' => 'Diameter',
							'min' => 10,
							'append' => 'px',
							'default_value' => '200'
						])
						->addField('position', 'dimensions', [
							'label' => 'Circle\'s Position',
							'return_format' => 'array',
							'wrapper' => [
								'class' => 'fr-custom-decoration'
							]
						])
					->endRepeater()
			->addAccordion('background_decoration_end')->endpoint();

		return $backgroundDecorationFields;
	}
}
