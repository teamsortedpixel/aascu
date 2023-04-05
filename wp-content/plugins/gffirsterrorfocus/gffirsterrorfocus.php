<?php
/**
 * Plugin Name:  Gravity Form Error Focus
 * Description:  Automatically focus (and scroll) to the field with a validation error.
 */
add_filter( 'gform_pre_render', function ( $form ) {
	add_filter( 'gform_confirmation_anchor_' . $form['id'], '__return_false' );

	if ( ! has_action( 'wp_footer', 'frgf_error_focus_script' ) ) {
		add_action( 'wp_footer', 'frgf_error_focus_script' );
		add_action( 'gform_preview_footer', 'frgf_error_focus_script' );
	}

	return $form;
} );

function frgf_error_focus_script() {
	?>
	<script type="text/javascript">
		if (window['jQuery']) {
			(function ($) {
				let formCloned = [];
				window['frgffef'] = [];
				
                // After form render
				$(document).on('gform_post_render', function (event, formId) {
					// AJAX-enabled forms will call gform_post_render again when rendering new pages or validation errors.
					// We need to reset our flag so that we can still do our focus action when the form conditional logic
					// has been re-evaluated.
					window['frgffef'][formId] = false;
					var hasError = frGfErrorFocus(formId).length;
					$(`#gform_confirmation_wrapper_${formId}`).remove();

					// Clone form on load
					if(!formCloned[formId]){
						$(`#gform_wrapper_${formId}`).after(`<div id="gform_clone_${formId}" class="gform_clone_container"></div>`);
						$(`#gform_clone_${formId}`).prepend($(`#gform_wrapper_${formId}`).clone());
						formCloned[formId] = true;
					}
				});

				// On error occurs
				$(document).on('gform_post_conditional_logic', function (event, formId, fields, isInit) {
					if (!window['frgffef'][formId] && fields === null && isInit === true) {
						frGfErrorFocus(formId);
						window['frgffef'][formId] = true;
					}
				});

				// On successfully submitted
				jQuery(document).on('gform_confirmation_loaded', function(event, formId){
					// Remove spinner and clone form again
					$(`#gform_ajax_spinner_${formId}`).remove();
					$(`#gform_confirmation_wrapper_${formId}`).before($(`#gform_wrapper_${formId}`).clone());
                });

				function frGfErrorFocus(formId) {
					var $firstError = $(`#gform_wrapper_${formId} .gfield.gfield_error:visible:first`);
					if ($firstError.length > 0) {
						// Without setTimeout or requestAnimationFrame, window.scroll/window.scrollTo are not taking
						// effect on iOS and Android.
						requestAnimationFrame(function () {
							window.scrollTo(0, $firstError.offset().top);
							$firstError.find('input, select, textarea').eq(0).focus();
						});
					}

					return $firstError;
				}
			})(jQuery);
		}
	</script>
	<?php
}