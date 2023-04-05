(function($) {
	$(() => {
		let libraryLoaded = false;
		let libraryIsLoading = false;

		const fetchLib = (callback) => {
			if(!libraryLoaded){
				if(!libraryIsLoading){
					libraryIsLoading = true;
					window.fetchInject([
						'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js',
					]).then(() => {
						libraryLoaded = true;
						console.log('Choices.js loaded');
						if(callback) callback();
					});    
				}
			}else{
				console.log('Choices.js already loaded');
				if(callback) callback();
			}
		}

		$(window).on('fr:load-choicesjs', (ev) => {
			fetchLib(() => {
				$(window).trigger('fr:choicesjs-loaded');
			});
		});
	});
})($);
