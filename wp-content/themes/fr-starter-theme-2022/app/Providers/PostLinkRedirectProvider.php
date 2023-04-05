<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PostLinkRedirectProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		add_action('template_redirect', function(){
			if(!function_exists('acf')) return;

			self::PostTemplateRedirect();
		});
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

	public static function PostTemplateRedirect(){
		global $post;

		if($post && !is_404() && !is_search() && in_array($post->post_type, ['news', 'resource', 'event', 'page'])){
			$post_link_type = get_field('post_link_type', $post);

			if($post_link_type){
				switch ($post_link_type) {
					case 'file':
						self::RedirectToFileDownload($post);
						break;
					
					case 'external':
						self::RedirectToExternalUrl($post);
						break;

					default:
						//Go On to Page Content (default Behaviour)
						break;
				}
			}
		}
	}

	public static function RedirectToFileDownload($post){
		if(!$post) return;

		$file = get_field('post_link_file', $post);

		if($file){
			$filename = $file['url'];

			$content = file_get_contents($filename);
			header('Content-Type: ' . $file['mime_type']);
			header('Content-Length: ' . strlen($content));
			header('Content-Disposition: inline; filename="'.basename( $filename ).'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			ini_set('zlib.output_compression','0');

			die($content);
		}
	}

	public static function RedirectToExternalUrl($post){
		if(!$post) return;
		$url = get_field('post_link_external_url', $post);

		if($url){
			wp_redirect($url);
			exit;
		}
	}


	/**
	 * Function that determines what is the target of a news, resource or event
	 * depending on if the redirection rules are set
	 *
	 * @param [type] $post
	 * @return void
	 */
	public static function PostLinkRedirectGetLinkTarget($post){
		$result = false;

		if($post){
			$post_link_type = get_field('post_link_type', $post);

			if($post_link_type){
				switch ($post_link_type) {
					case 'external':
					case 'file':
						$result = '_blank';
						break;
					default:
						//return false
						break;
				}
			}
		}
		

		return $result;
	}
}
