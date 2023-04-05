<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PersonRecaptchaProvider extends ServiceProvider
{

    const AJAX_ACTION = 'fr_recaptcha_validate_person';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
		add_action('wp_ajax_' . self::AJAX_ACTION , function(){
			return self::GetPostsAjax();
		});
		add_action('wp_ajax_nopriv_' . self::AJAX_ACTION , function(){
			return self::GetPostsAjax();
		});
    }

	public static function GetPostsAjax(){
		$args = [
			'token' => filter_input(INPUT_POST, 'token')?: false,
			'post_id' => filter_input(INPUT_POST, 'post_id')?: false,
			'nonce' => filter_input(INPUT_POST, 'nonce')?: false,
		];

        if(!wp_verify_nonce($args['nonce'], self::AJAX_ACTION)){
            wp_send_json_error([
                'msg' => 'Invalid Nonce'
            ]);
        }else{
            $isValid = self::sendCaptchaResponse($args['token']);

            if($isValid){
                $phone = get_field('phone', $args['post_id']);
                wp_send_json_success([
                    'phone_uri' => function_exists('acf') && is_object($phone) ? $phone->uri : false,
                    'phone_label' => function_exists('acf') && is_object($phone) ? $phone->international : false,
                ]);
            }else{
                wp_send_json_error([
                    'msg' => 'Invalid Recaptcha'
                ]);
            }
        }
	}

    /**
     * Will make it work if server does not have
     * allow_url_fopen
     */
    public static function fileGetContentsCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Build the captcha request URL
     */
    public static function buildCaptchaUrl($token)
    {
        $secret = isset(self::GetKeys()['secret_key']) ? self::GetKeys()['secret_key'] : false;
        return "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=" . $token . "&remoteip=" . $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Sends the captcha and returns true on success - else false
     */
    public static function sendCaptchaResponse($token)
    {
        $response = json_decode(self::fileGetContentsCurl(self::buildCaptchaUrl($token)), true);
        if ($response['success'] == false) {
            return false;
        }
        return true;
    }

    public static function GetAjaxConfig(){
        return wp_json_encode([
            'action' => self::AJAX_ACTION,
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(self::AJAX_ACTION)
        ]);
    }

    public static function GetKeys(){
        $result = [];

        if(class_exists('acf')){
            $site_key = get_field('recaptcha_settings', 'option')['recaptcha_site_key'];
            $secret_key = get_field('recaptcha_settings', 'option')['recaptcha_secret_key'];
            
            if($site_key && $secret_key){
                $result = [
                    'site_key' => $site_key,
                    'secret_key' => $secret_key
                ];
            }
        }

        return $result;
    }
}
