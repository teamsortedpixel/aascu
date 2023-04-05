<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class VideoEmbedWithCaption extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Video Embed With Caption';

     /**
	 * The block slug.
	 *
	 * @var string
	 */
    public $slug = 'fr-page-builder-module-video-embed-caption';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Video Embed With Caption block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'fr-page-builder-content-blocks';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = ' fricon dashicons-format-video';

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
    public $parent = ['acf/block-container'];

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
        'mode' => 'preview',
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'video' => '<div class="video-embed"><div class="thumbnail-container"><div class="inner-overlay"><img src="https://via.placeholder.com/960x540?text=Video+Preview"></div></div></div>',
        'caption' => 'Example data. Please add content.',
        'attributes' => [
            'preview_image' => 'video-embed-caption.png'
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        $data = [
            'video' => get_field('video') ? '<div class="video-embed">' . get_field('video') . '</div>' : false,
            'caption' => get_field('caption')
        ];

        if($this->preview && !$data['video']){
            return $this->example;
        }

        return $data;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $videoEmbedWithCaption = new FieldsBuilder('video_embed_with_caption');

        $videoEmbedWithCaption
            ->addOembed('video', [
                'required' => 1
            ])
            ->addTextarea('caption', [
                'rows' => 2,
                'new_lines' => 'br'
            ]);

        return $videoEmbedWithCaption->build();
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
