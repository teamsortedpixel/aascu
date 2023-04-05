<?php

	namespace App\Providers;

	use Roots\Acorn\ServiceProvider;

	class FrPageBuilderSettingsProvider extends ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			add_filter('acf/register_block_type_args', '\\App\Providers\FrPageBuilderSettingsProvider::addCustomIcon');
			add_filter('acf/register_block_type_args', '\\App\Providers\FrPageBuilderSettingsProvider::RenderImagePreview');
		}

		public static function addCustomIcon ($args){
			$path = get_template_directory() . '/resources/block-icons/' . str_replace("acf/", "", $args['name']) . '.svg';

			if(file_exists($path)){
				$args['icon'] =  preg_replace("/<\\?xml.*\\?>/", '', file_get_contents($path, FILE_USE_INCLUDE_PATH), 1);
			}

			return $args;
		}

		public static function RenderImagePreview($args){
			if( !isset($_POST) || !isset($_POST['block']) ) return $args;

			$post_data = json_decode(stripslashes($_POST['block']), true);

			if(isset($post_data['data'])  && isset($post_data['data']['attributes']['preview_image'])){
				$path = get_template_directory() . '/resources/block-previews/' . $post_data['data']['attributes']['preview_image'];
				$path_uri = get_template_directory_uri() . '/resources/block-previews/' . $post_data['data']['attributes']['preview_image'];

				if(file_exists($path)){
					$args['render_callback'] = function() use ($path_uri){
						echo '<img style="max-width:100%;width:100%;" src="'.$path_uri.'" />';
					};
				}
			}

			return $args;
		}
	}
