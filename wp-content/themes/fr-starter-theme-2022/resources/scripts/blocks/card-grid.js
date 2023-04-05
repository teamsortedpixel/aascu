(function ($) {
	const initializeSlider = ($self) => {
		new Splide($self.find('.card-grid-slider')[0], {
			type: 'loop',
			pagination: false,
			breakpoints: {
				768: {
				  focus: 'center',
				},
			  }
		}).mount();
	}

	$.fn.cardGrid = function () {
		return this.each((i, el) => {
			const $self = $(el);
			const $gridContainer = $self.find('.card-grid-container');

			if($gridContainer.hasClass('has-slider')){
				$(window).on("fr:splidejs-loaded", () => {
					initializeSlider($gridContainer);
				});
			}
		});
	}

	$(() => {
		$('.card-grid-module').cardGrid();
	});
})(jQuery);