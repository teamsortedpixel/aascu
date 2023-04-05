(function($) {
	$(() => {
		$(window).on('fr:update-url-params', (e, urlParams) => {
			const currPath = window.location.href.split('?')[0];
			const newUrl = currPath + (urlParams.length ? `?${urlParams}` : '');
			window.history.replaceState({}, '', newUrl);
		});
	});
})(jQuery);  