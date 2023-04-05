<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PhoneEmailButtons extends Component
{

    public $phone = false;
    public $email = false;
    public $phone_button_label = false;
    public $email_button_label = false;
    public $enable_recaptcha_validation = false;
    public $component_id = false;
    public $post_id = false;
    public $keys = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($phone = false, $email = false)
    {
        $this->phone = $phone && is_object($phone) ? $phone->uri : $phone;
        $this->email = $email ? 'mailto:'.$email : $email;

        $this->phone_button_label = $phone && is_object($phone) ? $phone->international : false; 
        $this->email_button_label = $this->email ? 'Send an email.' : false; 

        $this->component_id = uniqid('peb_');
        $this->post_id = get_the_ID();
        
        $this->keys = \App\Providers\PersonRecaptchaProvider::GetKeys();
        $this->enable_recaptcha_validation = !empty($this->keys);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.phone-email-buttons');
    }
}
