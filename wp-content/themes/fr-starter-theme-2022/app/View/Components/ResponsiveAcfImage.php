<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResponsiveAcfImage extends Component
{

    /**
     * The ACF image array, the value that comes from a acf field of type image when the return_format => array
     * 
     * If the array passed is NOT a ACF field value you can still use this by passing an array that has, at the very least
     * the URL parameter to the image. (alt is not required but it is always best practice to have it).
     *
     * @var array
     */
    public $image;

    /**
     * If you have the ID on the database for the image you want to show you can use this parameter alone and everything
     * will be rendered using the wp_get_attachment_image function 
     *
     * @var int|string
     */
    public $parameter_is_id;

    /**
     * The image size that you want to use, by default it uses the FULL size image. ONLY WORKS WHEN YOU PASS THE ID OF 
     * THE IMAGE FROM THE DATABASE OR PASS $image AS AN ACF FIELD (because it has the ID parameter on it as well).
     * 
     * The sizes come from wordpress: more info here https://themeisle.com/blog/wordpress-image-sizes/#default-image-size
     *
     * @var string
     */
    public $size;

    /**
     * CSS class to add to the <img> tag 
     *
     * @var string
     */
    public $class;

    /**
     * Style tag for inline styles.
     *
     * @var string
     */
    public $style;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($image = false, $size = false, $parameterIsId = false, $class = '', $style = '')
    {
        $this->image = $image;
        $this->size = $size;
        $this->parameter_is_id = (int) $parameterIsId;
        $this->class = $class;
        $this->style = $style;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.responsive-acf-image');
    }
}
