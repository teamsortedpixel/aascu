<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FrPageBuilder extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'FR Page Builder';

	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'fr-page-builder';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A simple Fr Page Builder block.';

	/**
	 * The block category.
	 *
	 * @var string
	 */
	public $category = 'fr-page-builder-blocks';

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
	public $parent = [];

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
		'anchor' => false,
		'mode' => false,
		'multiple' => false,
		'jsx' => true,
	];

    public $example = [
        'attributes' => [
            'preview_image' => 'fr-page-builder.png'
        ],
	];

	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		return [
			'allowed_blocks' => json_encode([
				'core/block',
				'acf/hero',
				'acf/event-hero',
				'acf/news-resources-hero',
				'acf/block-container'
			]),
			'theme_css' => $this->RenderProgramColorThemeCss()
		];
	}

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		return [];
	}

	/**
	 * Return the items field.
	 *
	 * @return array
	 */
	public function items()
	{
		return [ ];
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

	/**
	 * This will add a <style> tag to the top of the page builder container
	 * to "theme" or style specific elements on modules so that they are
	 * themed correctly according to designs.
	 *
	 * @return string $result
	 */
	public function RenderProgramColorThemeCss(){
		$result = '';

		if(!in_array(get_post_type(), ['page', 'news', 'event', 'resource'])) return $result;
		
		$program = \App\Providers\CardsDataProvider::getPrimaryTermByTaxonomy(get_the_ID(), 'program');

		if(!$program) return $result;

		$accent_color = get_field('accent_color', 'program_' . $program->term_id);
		$text_color = get_field('text_color', 'program_' . $program->term_id);

		$result = '<style>
			.circle-decoration.filled{
				background-color:'.$accent_color.' !important;
			}
			.fr-content-row:not(.section-bg-deep-navy) .circle-decoration.outline{
				border-color:'.$accent_color.' !important;
			}
			.card-grid-module .fr-card.condensed .card-inner {
				border-color: '.$accent_color.' !important;
			}
			.card-grid-module .fr-card.condensed.type-resource .card-inner .resource-image{
				border-color: '.$accent_color.' !important;
			}
			.fr-card.type-resource.card-type-regular .card-inner{
				border-color: '.$accent_color.' !important;
			}
			.fr-card.type-resource.card-type-regular .card-inner .resource-image{
				border-color: '.$accent_color.' !important;
			}
			.fr-card.type-resource.card-type-regular .card-inner .resource-image svg path{
				fill: '.$accent_color.' !important; 
			}
			.data-point-circle{
				border-color: '.$accent_color.' !important;
			}
			path.quick-link-svg{
				fill: '.$accent_color.' !important;
      		}
			path.quick-link-svg-text{
				fill: '.$text_color.' !important;
			}
			.module-testimonials-slider .left-container,.module-member-spotlight .left-container{
				background-color: '.$accent_color.' !important;
			}
			.module-member-spotlight .splide__slide, .module-testimonials-slider .splide__slide{
				border-color: '.$accent_color.' !important;
			}
		</style>';

		//remove line breaks and spacings before returning
		return trim(preg_replace('/\t+/', '', str_replace(array("\r", "\n"), '', $result)));
	}
}
