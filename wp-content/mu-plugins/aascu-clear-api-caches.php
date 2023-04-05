<?php
/*
	Plugin Name: AASCU Clear API Caches
	Plugin URI: https://freerange.com
	Description: AASCU Clear API Caches
	Version: 0.1
	Author: Dani Alvarez
	Author URI: https://freerange.com
*/

function aascucc_js_and_css(){
	$current_screen = get_current_screen();

	if($current_screen->base !== 'toplevel_page_theme-settings') return;

	?>
	<style>
		.aascu-cc_status:not(:empty){
			padding:20px 0px 10px 0;
		}
        .acf-field[data-name="aascu_clear_api_cache"] > .acf-input .acf-input-wrap{
            display: none;
        }
	</style>
	<script>
		function deferJq(method) {
			if (window.jQuery) {
				initializeJs();
			} else {
				setTimeout(function() { deferJq(method) }, 50);
			}
		}

		function updateMsg(msg) {
			var $status = jQuery('div.aascu-cc_status');
			$status.html(msg);
		}

		function initializeJs(){
			jQuery(document).ready(function(){
				var $ = jQuery;
				var $button = $('.button.aascu-cc_run-ajax');

				var ajaxConfig = JSON.parse($button.attr('ajax-config') || '{}');

				$button.on('click', function(){
					updateMsg('<i>Initializing...</i>');
					$button.addClass('disabled');
					//ajax
					do_ajax(ajaxConfig, function(resp){
						if(resp && resp.success){
                            updateMsg(`<i style="color:green;"><strong>Done.</strong> All caches cleared!</i>`);
                            $button.removeClass('disabled');
						}
					});
				});
			});
		}

		function do_ajax(ajaxConfig, callback){
			jQuery.ajax({
				type:"GET",
				url: ajaxConfig.url,
				data: {
					action: ajaxConfig.action,
				},
				success: function(data){
					callback(data);
				}

			});
		}

		//init
		deferJq();

	</script>
	<?php
}

function aascucc_render_field($ajax_config){
	echo '<a class="acf-button button button-primary aascu-cc_run-ajax" href="javascript:void(0)" ajax-config=\''.wp_json_encode($ajax_config).'\'>Clear all API caches</a>';
	echo '<div class="aascu-cc_status"></div>';
}

function aascucc_get_all_ids_by_post_type($post_types = []){
	$result = ['ids' => [], 'count' => 0];
	$args = [
		'post_type' => $post_types,
		'status' => ['publish'],
		'posts_per_page' => -1
	];
	
	// The Query
	$query = new WP_Query( $args );
	wp_reset_postdata();

	foreach ($query->posts as $p) {
		$result['ids'][] = $p->ID;
	}

	$result['count'] = count($query->posts);
	
	return $result;
}

function aascucc_ajax(){
	$args = [];

    $result = do_action('aascu_clear_api_caches_action');

	if(is_wp_error($result)){
		wp_send_json_error($result);
	}else{
		wp_send_json_success($result);
	}
}

function aascucc_initialize(){
	
	add_action( 'admin_head', function(){
		aascucc_js_and_css();
	});

	add_action('acf/render_field/name=aascu_clear_api_cache', function(){
		aascucc_render_field([
			'action' => 'aascu_clear_api_cache',
			'url' => admin_url('admin-ajax.php')
		]);
	});

	add_action('wp_ajax_aascu_clear_api_cache', 'aascucc_ajax');
}


add_action('after_setup_theme', function(){
	if(!class_exists('acf')) return;
	aascucc_initialize();
});