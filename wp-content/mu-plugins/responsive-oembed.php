<?php
	//https://gist.github.com/ihorvorotnov/9415356
	/**
	 * Wrap videos embedded via oEmbed to make them responsive
	 */
	function p2_wrap_oembed( $html, $url, $attr, $post_id ) {
		return '<div class="video-embed">' . $html . '</div>';
	}
	add_filter( 'embed_oembed_html', 'p2_wrap_oembed', 99, 4 );