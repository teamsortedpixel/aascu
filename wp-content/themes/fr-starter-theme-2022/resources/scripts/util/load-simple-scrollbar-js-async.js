(function($) {
	$(() => {
		const $container = $('.requires-simplescrollbarjs');
		if($container.length){
			window.fetchInject([
				'https://unpkg.com/simplebar@5.3.9/dist/simplebar.min.js',
			]).then(() => {
				$(window).trigger('fr:simplescrollbarjs-loaded');
			});
		}
	});
})($);