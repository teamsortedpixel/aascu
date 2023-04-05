(function ($) {
	$.fn.customDropdowns = function () {
		const isEmptyObj = (obj) => {
			return Object.keys(obj).length === 0;
		}

		const getDefaultConfig = () => {
			return {
				searchEnabled: false,
				itemSelectText: ''
			}
		}

		return this.each((i, el) => {
			const $self = $(el);
			const choicesConfig = JSON.parse($self.attr('choices-config') || '{}');
			const defaultConfig = getDefaultConfig();
			const choices = new Choices($self[0], isEmptyObj(choicesConfig) ? defaultConfig : choicesConfig);
		});
	}

	$(() => {
		const $dropdowns = $('.fr-custom-dropdown, .ginput_container_select > select');
		if($dropdowns.length){
			$(window).trigger('fr:load-choicesjs');
			$(window).on('fr:choicesjs-loaded', () => {
				$($dropdowns).customDropdowns();
			});
		}
	});
})($);