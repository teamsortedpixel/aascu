<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SinglePeople extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'single-people',
	];

	/**
	 * Data to be passed to view before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		$post_id = get_the_ID();

		return [
			'hero_image' => $this->getHeroImage($post_id),
			'hero_content' => $this->generateHeroContent($post_id),
			'circles' => $this->getCirclesConfig(),
			'extra_settings' => $this->getExtraSettings(),
			'bio' => get_field('bio', $post_id),
			'bio_block_settings' => $this->getBioBlockSettings()
		];
	}

	public function generateHeroContent($post_id){
		$result = '';

		//fields
		$firstname = get_field('firstname', $post_id);
		$lastname = get_field('lastname', $post_id);
		$title = get_field('title', $post_id);
		$organization = get_field('description', $post_id);
		$phone = get_field('phone', $post_id);
		$email = get_field('email', $post_id);
		$socials = get_field('social_links', $post_id) ?: [];

		//title
		$result .= '<h1>'.$firstname.' '.$lastname.'</h1>';

		//Title
		$result .= $title ? '<h4><span class="aascu-red">'.$title.'</span></h4>' : '';

		//Org
		$result .= $organization ? '<h5>'.$organization.'</h5>' : '';

		//phone & email
		if($phone || $email){
			$phone_email_buttons = new \App\View\Components\PhoneEmailButtons($phone, $email);
			$result .= $phone_email_buttons->render()->with($phone_email_buttons->data());
		}
		
		//socials
		if($socials && !empty($socials)){
			$socials_icons = new \App\View\Components\SocialLinks($socials);
			$result .= $socials_icons->render()->with($socials_icons->data());
		}

		return $result;
	}

	public function getHeroImage($post_id){
		return get_field('profile_photo', $post_id);
	}

	public function getExtraSettings(){
		$obj = new \stdClass();
		$obj->classes = 'people-hero';

		return $obj;
	}

	public function getBioBlockSettings(){
		$obj = new \stdClass();
		$obj->classes = '';

		return $obj;
	}

	public function getCirclesConfig(){
		return [
			0 => [
				'style' => 'filled',
				'size' => '319', 
				'position' => [
					'desktop' => [
						'top' => '12',
						'left' => '-250',
						'unit' => 'px'
					],
					'tablet' => [
						'top' => '',
						'right' => '',
						'bottom' => '',
						'left' => '',
						'linked' =>'0',
						'unit' => 'px'
					],
					'mobile' => [
						'top' => '-1000',
						'right' => '-1000',
						'bottom' => '',
						'left' => '',
						'linked' =>'0',
						'unit' => 'px'
					]
				]
			],
			1 => [
				'style' => 'outline',
				'size' => '249',
				'position' => [
					'desktop' => [
						'top' => '',
						'left' => '',
						'bottom' => '-26',
						'right' => '73',
						'unit' => 'px',
						'linked' =>'0',
					],
					'tablet' => [
						'top' => '',
						'right' => '',
						'bottom' => '',
						'left' => '',
						'linked' =>'0',
						'unit' => 'px'
					],
					'mobile' => [
						'top' => '-1000',
						'right' => '-1000',
						'bottom' => '',
						'left' => '',
						'linked' =>'0',
						'unit' => 'px'
					]
				] 
			],
			3 => [
				'style' => 'filled',
				'size' => '225',
				'position' => [
					'desktop' => [
						'top' => '',
						'left' => '',
						'bottom' => '-1000',
						'right' => '-1000',
						'unit' => 'px',
						'linked' =>'0',
					],
					'tablet' => [
						'top' => '',
						'right' => '',
						'bottom' => '',
						'left' => '',
						'linked' =>'0',
						'unit' => 'px'
					],
					'mobile' => [
						'top' => '19',
						'right' => '',
						'bottom' => '',
						'left' => '-100.5',
						'linked' =>'0',
						'unit' => 'px'
					]
				] 
			]
		];
	}
}
