(function($) {
	$(() => {
		const $container = $('.requires-panzoomjs');
		let libraryLoaded = false;
		let libraryIsLoading = false;

		const fetchLib = (callback) => {
			if(!libraryLoaded){
				if(!libraryIsLoading){
					libraryIsLoading = true;
					window.fetchInject([
						'https://unpkg.com/@panzoom/panzoom@4.5.1/dist/panzoom.min.js',
					]).then(() => {
						libraryLoaded = true;
						console.log('Panzoom.js loaded');
						if(callback) callback();
					});    
				}
			}else{
				console.log('Panzoom.js already loaded');
				if(callback) callback();
			}
		}

		$(window).on('fr:load-panzoomjs', (ev) => {
			fetchLib(() => {
				$(window).trigger('fr:panzoomjs-loaded');
			});
		})

		if($container.length){
			fetchLib(() => {
				$(window).trigger('fr:panzoomjs-loaded');
			});
		}
	});
})($);
