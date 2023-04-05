<?php
/**
 * Taken from this: https://gist.github.com/blacksaildivision/d74b3f92faf7f8a8b3a7d88cf7cd713e
 */

namespace App\Providers;

use Roots\Acorn\ServiceProvider;

class HeaderCleanUpProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		add_filter('wp_resource_hints','\\App\Providers\HeaderCleanUpProvider::wpResourceHints', 10, 2);
		add_filter('after_theme_setup','\\App\Providers\HeaderCleanUpProvider::disableRssRedirect', 10, 2);
		add_filter('rest_authentication_errors', '\\App\Providers\HeaderCleanUpProvider::restAuthErrors');
		add_filter('style_loader_tag', '\\App\Providers\HeaderCleanUpProvider::styleLoaderTag', 10, 2);

		/**
		 * Remove the feed links from <head>
		 */
		remove_action('wp_head', 'feed_links', 2);

		/**
		 * Remove emoji script and styles from <head>
		 */
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');

		/**
		 * Remove REST-AI link from <head>
		 */
		remove_action('wp_head', 'rest_output_link_wp_head');

		/**
		 * Remove XML-RPC link from <head>
		 */
		remove_action('wp_head', 'rsd_link');

		/**
		 * Remove Windows Live Writer manifest from <head>
		 */
		remove_action('wp_head', 'wlwmanifest_link');

		/**
		 * Remove info about WordPress version from <head>
		 */
		remove_action('wp_head', 'wp_generator');

		/**
		 * Disable XML-RPC
		 */
		add_filter('xmlrpc_enabled', function () {
			return false;
		});

		/**
		 * Remove Gutenberg default styles
		 */
		add_action('wp_print_styles', function () {
			wp_dequeue_style('wp-block-library');
			wp_dequeue_style('wp-block-library-theme');
		});
	}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

	/**
	 * Alter dns-prefetch links in <head>
	 */
	public static function wpResourceHints($urls, $relation){
		// If the relation is different than dns-prefetch, leave the URLs intact
		if ($relation !== 'dns-prefetch') {
			return $urls;
		}

		// Remove s.w.org entry
		$urls = array_filter($urls, function (string $url): bool {
			return strpos($url, 's.w.org') === false;
		});

		// List of domains to prefetch:
		$dnsPrefetchUrls = [];
		return array_merge($urls, $dnsPrefetchUrls);
	}

	/**
	 * Disable RSS feeds by redirecting their URLs to homepage
	 */
	public static function disableRssRedirect(){
		foreach (['do_feed_rss2', 'do_feed_rss2_comments'] as $feedAction) {
			add_action($feedAction, function (): void {
				// Redirect permanently to homepage
				wp_redirect(home_url(), 301);
				exit;
			}, 1);
		}
	}

	/**
	 * Disable REST-API for all users except of admin. And, for AASCU, Contributors
	 */
	public static function restAuthErrors ($access) {
		if (!current_user_can('administrator') && !current_user_can('contributor')) {
			return new \WP_Error('rest_cannot_access', 'Only authenticated users can access the REST API.', ['status' => rest_authorization_required_code()]);
		}
		return $access;
	}

	/**
	 * Remove unnecessary attributes from style tags
	 */
	public static function styleLoaderTag($tag, $handle){
		// Remove ID attribute
		$tag = str_replace("id='${handle}-css'", '', $tag);

		// Remove type attribute
		$tag = str_replace(" type='text/css'", '', $tag);

		// Change ' to " in attributes:
		$tag = str_replace('\'', '"', $tag);

		// Remove trailing slash
		$tag = str_replace(' />', '>', $tag);

		// Remove double spaces
		return str_replace('  ', '', $tag);
	}
}
