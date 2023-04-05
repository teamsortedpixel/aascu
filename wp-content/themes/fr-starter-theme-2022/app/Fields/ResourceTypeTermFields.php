<?php

	namespace App\Fields;

	use Log1x\AcfComposer\Field;
	use StoutLogic\AcfBuilder\FieldsBuilder;

	class ResourceTypeTermFields extends Field
	{
		/**
		 * The field group.
		 *
		 * @return array
		 */
		public function fields()
		{
			$resourceTypeTermFields = new FieldsBuilder('resource_type_term_fields', [
				'title' => 'Resource Type Fields'
			]);

			$resourceTypeTermFields
				->setLocation('taxonomy', '==', 'resource-type');

			$resourceTypeTermFields
				->addImage('icon', [
					'mime_types' => 'svg'
				]);

			return $resourceTypeTermFields->build();
		}
	}
