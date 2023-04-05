<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Partial;
use StoutLogic\AcfBuilder\FieldsBuilder;

class SocialLinks extends Partial
{
	/**
	 * The partial field group.
	 *
	 * @return \StoutLogic\AcfBuilder\FieldsBuilder
	 */
	public function fields()
	{
		$socialLinks = new FieldsBuilder('social_links');

		$socialLinks
		->addRepeater('social_links', [
			'layout' => 'block',
		])
			->addSelect('type', [
				'allow_null' => 1,
				'choices' => [
					'fb' => 'Facebook',
					'ln' => 'LinkedIn',
					'tw' => 'Twitter',
					'md' => 'Medium',
					'yt' => 'YouTube'
				],
				'return_format' => 'array',
				'wrapper' => [
					'width' => 35
				],
			])
			->addUrl('url', [
				'wrapper' => [
					'width' => 65
				],
			])
		->endRepeater();

		return $socialLinks;
	}
}
