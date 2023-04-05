<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CtaButton extends Component
{

    public $label;
    public $type;
    public $postId;
    public $externalUrl;
    public $newTab;
    public $openModal;
    public $style;
    public $arrow;
    public $url;
    public $target;
    public $preview;
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = '', $type = '', $postId = '', $style = '', $arrow = true, $externalUrl = false, $anchor = false, $newTab = false, $openModal = false, $preview = false, $icon= false)
    {
        $this->label = $label;
        $this->preview = $preview;
        $this->url = $this->getUrl($type, $postId, $externalUrl, $anchor);
        $this->target = $newTab ? '_blank' : '';

        // Because of style changes support for old data entries
        if($style){
            $style = trim(str_replace(['light', 'dark'], '', $style));
        }
        $this->style = $style && in_array($style, $this->getStyles()) ? $style : $this->getStyles()[0]; 
        $this->arrow = $arrow;
        $this->icon = $icon && in_array($icon, $this->getIcons()) ? $icon : false;
    }

    protected function getUrl($type, $postId, $externalUrl, $anchor){
        $result = '';
        if($type === 'external_url' || $type === 'externa_url'){
            $result = $externalUrl;
        }else if($type === 'anchor'){
            $result =  '#' . ($anchor ? trim(str_replace('#', '', $anchor)) : '');
        }else{
            $result = get_the_permalink($postId);
        }

        return $result;
    }

    public static function getStyles(){
        return [
            'primary',
            'secondary',
            'tertiary'
        ];
    }

    public static function getIcons(){
        return [
            'phone',
            'email'
        ];
    }
    

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cta-button');
    }
}
