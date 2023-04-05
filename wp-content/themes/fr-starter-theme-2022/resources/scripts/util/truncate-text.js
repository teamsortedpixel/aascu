/* 
	This uses the Shave.js library (https://jeffry.in/shave/) and I built it in such a way that
	you can call a custom event on the window object so that:
	First, it loads the library dynamically so we don't have to load the library on all the pages
	Second, once it's loaded it proceeds to truncate the element passed as the first parameter, the height in pixel to truncate,
	and using the configuration object passed as the third parameter

	READ MORE HERE: https://github.com/yowainwright/shave
*/

(function($) {
	$(() => {
		const TruncateText = (element, height, config, callback) => {
			shave(element[0], height, config || {});

			if(callback){
				callback();
			}
		}

		const executeQueue = (queue) => {
			queue.forEach(el => {
				TruncateText(el.element, el.height, el.config, el.callback);
			});
		}

		//VARS
		window.shaveJsLoaded = false;
		window.shaveJsLoading = false;
		window.shaveJsExecutionQueue = [];

		/**
		 * On any js file we can call it like this: 
		 * $(window).trigger('fr:truncate-text', [$('.some-element'), height, { character: "..." }])
		 */
		$(window).on('fr:truncate-text', (ev, element, height, config, callback) => {
			//prevent loading the library multiple times once it has been fetched
			if(!window.shaveJsLoaded){
				//add all the events on a queue so they can be executed once the library loads
				window.shaveJsExecutionQueue.push({
					element: element,
					height: height,
					config: config,
					callback: callback
				});
			}

			if(!window.shaveJsLoaded){
				if(!window.shaveJsLoading){
					//prevents fetchInject to be called multiple times
					window.shaveJsLoading = true;

					window.fetchInject([
						'https://unpkg.com/shave@3.0.0/dist/shave.min.js',
					]).then(() => {
						//already loaded
						window.shaveJsLoaded = true;
						window.shaveJsLoading = false;

						//run queue
						executeQueue(window.shaveJsExecutionQueue);
					});
				}
			}else{
				//library already available
				//call function to truncate
				TruncateText(element, height, config);
			}
		})
	});
})(jQuery);
