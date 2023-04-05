<?php

	namespace App\Fields;

	use Log1x\AcfComposer\Field;
	use StoutLogic\AcfBuilder\FieldsBuilder;

	class SideNavQuickLinks extends Field
	{
		/**
		 * The field group.
		 *
		 * @return array
		 */
		public function fields()
		{
			$sideNavQuickLinks = new FieldsBuilder('side_nav_quick_links', [
				'title' => 'Side Navigation: Quick Links',
				'position' => 'side'
			]);

			$sideNavQuickLinks
				->setLocation('post_type', '==', 'post')
					->or('post_type', '==', 'page')
					->or('post_type', '==', 'news')
					->or('post_type', '==', 'resource')
					->or('post_type', '==', 'event');

			$sideNavQuickLinks
				->addTrueFalse('enable_side_nav', [
					'label' => 'Enable Side Navigation?'
				])
				->addGroup('side_nav', [
					'label' => 'Side Navigation',
					'layout' => 'block',
					'instructions' => 'For each Anchor element: Enter a word or two — without spaces — to make a unique web address just for a specific block or section on the page, called an “anchor.” Then, you’ll be able to link directly to this section of your page.',
				])
					->conditional('enable_side_nav', '==', 1)
					->addRepeater('anchors', [
						'layout' => 'block',
						'collapsed' => 'label'
					])
						->addText('label', [
							'required' => 1,
						])
						->addText('anchor', [
							'required' => 1,
							'wrapper' => [
								'class' => 'fr-validate-anchor-field'
							] 
						])
					->endRepeater()
				->endGroup();

			return $sideNavQuickLinks->build();
		}
	}
