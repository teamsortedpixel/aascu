(($) => {
	const scrollTo = function(el){
		const y = el.getBoundingClientRect().top + window.pageYOffset;
		window.scroll({
			behavior: 'smooth',
			left: 0,
			top: y,
		});
	}

	$(window).on('load', () => {
		let hash = window.location.hash.replace('#', '');

		if(hash.length === 0) return;

		const $elem = $(`#${hash}`);

		if($elem.length && hash.length){
			scrollTo($elem[0]);
		}
	});
})($);