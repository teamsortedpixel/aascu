<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Partials\BackgroundDecorationFields;

class BlockContainer extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'Block Container';
	
	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'block-container';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A Block Container block.';

	/**
	 * The block category.
	 *
	 * @var string
	 */
	public $category = 'fr-page-builder-layout-blocks';

	/**
	 * The block icon.
	 *
	 * @var string|array
	 */
	public $icon = '';

	/**
	 * The block keywords.
	 *
	 * @var array
	 */
	public $keywords = [];

	/**
	 * The block post type allow list.
	 *
	 * @var array
	 */
	public $post_types = [];

	/**
	 * The parent block type allow list.
	 *
	 * @var array
	 */
	public $parent = ['acf/fr-page-builder'];

	/**
	 * The default block mode.
	 *
	 * @var string
	 */
	public $mode = 'preview';

	/**
	 * The default block alignment.
	 *
	 * @var string
	 */
	public $align = '';

	/**
	 * The default block text alignment.
	 *
	 * @var string
	 */
	public $align_text = '';

	/**
	 * The default block content alignment.
	 *
	 * @var string
	 */
	public $align_content = '';

	/**
	 * The supported block features.
	 *
	 * @var array
	 */
	public $supports = [
		'align' => false,
		'align_text' => false,
		'align_content' => false,
		'full_height' => false,
		'anchor' => true,
		'mode' => false,
		'multiple' => true,
		'jsx' => true,
	];


	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		//allowed blocks
		$prefix = 'acf/fr-page-builder-module-';
		$allowed_blocks = [];
		foreach ($registered_blocks as $b) {
			if(substr( $b->name, 0, strlen($prefix) ) === $prefix) {
				$allowed_blocks[] = $b;
			}
		}

		//Allow gravity forms to be added to block container
		$allowed_blocks[] = 'gravityforms/form';

		//Allow reusable blocks
		$allowed_blocks[] = 'core/block';

		return array_merge($this->items(), [
			'allowed_blocks' => json_encode($allowed_blocks)
		]);
	}

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$contentSection = new FieldsBuilder('content_section');

		$contentSection
			->addRadio('background_color', [
				'choices' => [
					'#fff' => 'White',
					'#F8F8F8' => 'Light Gray',
					'#1E2642' => 'Deep Navy'
				],
				'wpml_cf_preferences' => 0,
				'return_format' => 'array',
				'default' => '#fff'
			])
			->addRadio('content_max_width', [
				'label' => 'Content\'s Max Width',
				'layout' => 'horizontal',
				'wpml_cf_preferences' => 0,
				'choices' => [
					'default' => 'Default',
					'full_width' => 'Full Screen Width',
					'custom' => 'Custom'
				],
				'default_value' => 'default'
			])
			->addRange('custom_content_max_width', [
				'label' => 'Content\'s Max Width: Custom',
				'min' => '0',
				'max' => '100',
				'append' => '%',
				'default_value' => '100'
			])
				->conditional('content_max_width', '==', 'custom')
			->addAccordion('background_image')
				->addImage('background_image', [
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addRadio('background_image_size', [
					'label' => 'Background Image: Desktop Size',
					'choices' => [
						'contain' => 'Contained (Expands vertically and horizontally to cover the available space)',
						'auto' => 'Auto (Image\'s real size)'
					],
					'default_value' => 'contain',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addImage('background_image_mobile', [
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addRadio('background_image_size_mobile', [
					'label' => 'Background Image: Mobile Size',
					'choices' => [
						'contain' => 'Contain (Expands vertically and horizontally to cover space)',
						'auto' => 'Auto (image\'s real size)'
					],
					'default_value' => 'contain',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addField('background_image_dimensions', 'dimensions', [
					'label' => 'Background Image: Margins',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
					'return_format' => 'array'
				])
					->conditional('background_image_size', '==', 'auto')
						->or('background_image_size_mobile', '==', 'auto')
				->addTrueFalse('background_color_overlay', [
					'label' => 'Enable Color Overlay?'
				])
				->addTrueFalse('enable_custom_color_overlay', [
					'label' => 'Select Custom Color'
				])
					->conditional('background_color_overlay', '==', '1')
				->addColorPicker('custom_overlay_color', [
					'label' => 'Custom Overlay Color'
				])
					->conditional('background_color_overlay', '==', '1')
					->and('enable_custom_color_overlay', '==', '1')
				->addRange('background_color_overlay_opacity', [
					'label' => 'Overlay Opacity',
					'min' => '0',
					'max' => '100',
					'append' => '%',
					'default_value' => '50'
				])
					->conditional('background_color_overlay', '==', '1')
				->addTrueFalse('glass_effect', [
					'label' => 'Enable Glass Effect?',
					'wrapper' => [
						'class' => 'hidden'
					]
				])					
			->addAccordion('background_image_end')->endpoint()
			->addFields($this->get(BackgroundDecorationFields::class))
			->addAccordion('height')
				->addRadio('min_height', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'default' => 'Default',
						'custom' => 'Custom'
					]
				])
				->addNumber('min_height_value', [
					'label' => 'Add Min Height',
					'min' => 0,
					'append' => 'px'
				])
					->conditional('min_height', '!=', 'default')
			->addAccordion('height_end')->endpoint()
			->addAccordion('padding')
				->addButtonGroup('vertical_padding', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'default' => 'Default',
						'small' => 'Small',
						'none' => 'none'
					],
					'default_value' => 'default'
				])
			->addAccordion('padding_end')->endpoint()
			->addAccordion('content')
				->addButtonGroup('vertically_stack_content', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'top' => 'Top',
						'middle' => 'Middle',
						'bottom' => 'Bottom'
					],
					'default_value' => 'middle'
				])
			->addAccordion('content_end')->endpoint();

		return $contentSection->build();
	}

	public function getContentMaxWidth()
	{
		$result = '';
		$content_max_width = get_field('content_max_width');
		
		switch ($content_max_width) {
			case 'default':
				$result = '';
				break;
			case 'full_width':
				$result = 'full-width';
				break;
			case 'custom':
				$result = 'custom';
				break;
			default:
				break;
		}

		return $result;
	}

	public function getCustomContentMaxWidthClass(){
		$content_max_width = $this->getContentMaxWidth();
		return $content_max_width == 'custom' ? 'fr-container--max-'.get_field('custom_content_max_width') : false;
	}

	public function getColorOverlayAttr(){
		$result = [];
		$background_color = get_field('background_color') ? get_field('background_color')['value'] : false;
		$enabled = get_field('background_color_overlay');
		$opacity = get_field('background_color_overlay_opacity');
		
		if($enabled){
			$custom_color = get_field('enable_custom_color_overlay') ? get_field('custom_overlay_color') : $background_color;

			$result = array_filter([
				$custom_color ? 'background-color:'.$custom_color : false,
				$opacity ? 'opacity:' .floatval($opacity / 100) : false
			]);
		}

		return 'style="' . implode(';', $result) . '"';
	}

	public function getMinHeight(){
		$result = [];
		$min_height = get_field('min_height');
		if($min_height && $min_height !== 'default'){
			$result = [';min-height:'.get_field('min_height_value').'px;'];
		}

		return $result;
	}

	public function getContainerAtts(){
		$result = '';

		$acc = [];

		$acc = array_merge( $acc, array_filter( $this->getMinHeight() ) );

		$result = 'style="' . implode(';', $acc) . '"';

		return $result;
	}

	/**
	 * Return the items field.
	 *
	 * @return array
	 */
	public function items()
	{
		$bg_color = get_field('background_color') ? get_field('background_color')['label'] : '';
		return [
			'background_color' => get_field('background_color') ? 'section-bg-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $bg_color))) : 'section-bg-white',
			'background_image' => get_field('background_image'),
			'background_image_size' => get_field('background_image_size'),
			'background_image_dimensions' => $this->createCssRules('desktop', get_field('background_image_dimensions')),
			'content_max_width' => $this->getContentMaxWidth(),
			'container_atts' => $this->getContainerAtts(),
			'background_color_overlay' => $this->getColorOverlayAttr(),
			'custom_max_width_class' => $this->getCustomContentMaxWidthClass(),
			'background_image_mobile' =>  get_field('background_image_mobile') ?: get_field('background_image'),
			'background_image_mobile_size' => get_field('background_image_mobile_size'),
			'background_image_dimensions_mobile' => $this->createCssRules('mobile', get_field('background_image_dimensions')),
			'glass_effect' => get_field('glass_effect'),
			'vertical_padding' => get_field('vertical_padding'),
			'vertically_stack_content' => get_field('vertically_stack_content'),
			'circles' => $this->getCirclesConfiguration(get_field('circle_1'), get_field('circle_2'), get_field('circles') ?: [])
		];
	}

	public function createCssRules($breakpoint, $margins) {
		if(!$margins) return '';

		$css = [
			($margins[$breakpoint]['top'] === 'auto' ? $margins[$breakpoint]['top'] : $margins[$breakpoint]['top'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['right'] === 'auto' ? $margins[$breakpoint]['right'] : $margins[$breakpoint]['right'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['bottom'] === 'auto' ? $margins[$breakpoint]['bottom'] : $margins[$breakpoint]['bottom'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['left'] === 'auto' ? $margins[$breakpoint]['left'] : $margins[$breakpoint]['left'].$margins[$breakpoint]['unit']),
        ];

		return implode(' ', $css);
	}

	public function getCirclesConfiguration($circle_1, $circle_2, $advanced){
		$result = [];

		if(!$circle_1 && !$circle_2) return $advanced;

		if($circle_1 == 'none' && $circle_2 == 'none'){
			return $advanced;
		}

		if(in_array($circle_1, ['top-solid', 'left-solid'])){
			$result[] = $this->getCircleConfiguration($circle_1);
		}

		if(in_array($circle_2, ['bottom-outline', 'right-outline'])){
			$result[] = $this->getCircleConfiguration($circle_2);
		}

		return array_filter($result);
	}

	public function getCircleConfiguration($option){
		$result = false;

		if(!$option || $option == 'none') return $result;

		switch ($option) {
			case 'top-solid':
				$result = [
					'style' => 'filled',
					'size' => '280', 
					'size_mobile' => '225',
					'position' => [
						'desktop' => [
							'top' => '-140',
							'right' => '',
							'left' => '0',
							'bottom' => '',
							'unit' => 'px'
						],
						'mobile' => [
							'top' => '-112.5',
							'right' => '',
							'bottom' => '',
							'left' => '12',
							'unit' => 'px'
						]
					]
				];
				break;

			case 'left-solid':
				$result = [
					'style' => 'filled',
					'size' => '280', 
					'size_mobile' => '225',
					'position' => [
						'desktop' => [
							'top' => '50',
							'right' => '',
							'left' => '-200',
							'bottom' => '',
							'unit' => 'px'
						],
						'mobile' => [
							'top' => '',
							'right' => '',
							'left' => '-105.5',
							'bottom' => '',
							'unit' => 'px'
						]
					]
				];
				break;

			case 'right-outline':
				$result = [
					'style' => 'outline',
					'size' => '225',
					'size_mobile' => '180',
					'position' => [
						'desktop' => [
							'top' => 'calc(50% - 112.5px)',
							'right' => '-112.5px',
							'left' => '',
							'bottom' => '',
							'unit' => ''
						],
						'mobile' => [
							'top' => 'calc(50% - 90px)',
							'right' => '-80px',
							'left' => '',
							'bottom' => '',
							'unit' => ''
						]
					]
				];
				break;
			case 'bottom-outline':
				$result = [
					'style' => 'outline',
					'size' => '225',
					'size_mobile' => '180',
					'position' => [
						'desktop' => [
							'top' => '',
							'right' => '0',
							'left' => '',
							'bottom' => '-125',
							'unit' => 'px'
						],
						'mobile' => [
							'top' => '',
							'right' => '12',
							'bottom' => '-90',
							'left' => '',
							'unit' => 'px'
						]
					]
				];
				break;
			default:
				# code...
				break;
		}
		return $result;
	}
	
	/**
	 * Assets to be enqueued when rendering the block.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		//
	}
}
