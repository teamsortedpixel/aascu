<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiProvider extends ServiceProvider
{
	const TOKEN_OPTION_NAME = 'aascu_api_access_token';
	const MAP_DATA_OPTION_NAME = 'aascu_api_map_data';

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		add_action('aascu_clear_api_caches_action', function(){
			delete_option(self::TOKEN_OPTION_NAME);
			delete_option(self::MAP_DATA_OPTION_NAME);
		},10, 1);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	public static function setApiAccessToken($api_token_response){
		$now_unix = time();

		if( isset($api_token_response) && is_array($api_token_response) && $api_token_response['access_token'] && $api_token_response['expires_in'] ){
			update_option(self::TOKEN_OPTION_NAME, [
				'access_token' => $api_token_response['access_token'],
				'expiration_date_unix' => $api_token_response['expires_in'] + $now_unix
			]);
		}
	}

	public static function setApiMapData($api_map_response){
		$now_unix = time();
		$time_offset = 1209600; //14 days in seconds

		if($api_map_response){
			update_option(self::MAP_DATA_OPTION_NAME, [
				'data' => $api_map_response,
				'expiration_date_unix' => $now_unix + $time_offset
			]);
		}
	}


	public static function isAccessTokenExpired(){
		$saved_option = self::getToken();

		if(!$saved_option) return true;

		$now = time();

		if($now > $saved_option['expiration_date_unix']){
			return true;
		}else{
			return false;
		}
	}
	
	public static function getToken(){
		return get_option(self::TOKEN_OPTION_NAME, null);
	}

	public static function requestToken(){
		$base_url = function_exists('acf') ? get_field('api_base_url', 'option') : '';
		$uname = function_exists('acf') ? get_field('api_username', 'option') : '';
		$passw = function_exists('acf') ? get_field('api_password', 'option') : '';

		$headers = [
			'content-type' => 'application/x-www-form-urlencoded'
		];

		$params = [
			'grant_type' => 'password',
			'username' => $uname,
			'password' => $passw,
		];

		//set curl options
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

		//do api call
		$result = curl_exec($ch);

		return !is_wp_error($result) ? json_decode($result, true) : false;
	}

	public static function requestMapData(){
		$result = false;
		$access_token = self::getToken();
		$url = function_exists('acf') ? get_field('api_base_url', 'option') . '/api/iqa' : ''; 

		if(!$access_token || self::isAccessTokenExpired()){
			$access_token = self::requestToken();
			self::setApiAccessToken($access_token);
		}

		if(isset($access_token) && is_array($access_token) && !is_wp_error($access_token)){
			//set curl options
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url .'?'. http_build_query([
				'QueryName' => '$/AASCU Website/ContactManagement/OrganizationRosterMemberMap&limit=10000'
			]));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json; charset=utf-8',
				'Authorization: Bearer ' . $access_token['access_token']
			]);

			//do api call
			$result = curl_exec($ch);
		}

		return !is_wp_error($result) ? json_decode($result, true) : false;
	}

	public static function getMapData(){
		$now = time();
		$data = get_option(self::MAP_DATA_OPTION_NAME, null);

		//if there is no cached data, pull api
		if(!$data || $now >= $data['expiration_date_unix']){
			$data = self::requestMapData();
			self::setApiMapData($data);
			$data = get_option(self::MAP_DATA_OPTION_NAME, null);
		}

		return $data;
	}
}
