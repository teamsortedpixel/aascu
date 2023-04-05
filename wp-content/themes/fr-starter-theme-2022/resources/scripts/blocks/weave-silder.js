(function ($) {
	$.fn.weaveSlider = function () {
		const setContainerWidth = ($container) => {
			$container.find('.side-inner').css('width', `${$container.closest('.weave-slider-inner').width()}px`);
		}

		return this.each((i, el) => {
			const $self = $(el);
			const $slider = $self.find('input.slider-input');
			const $leftContainer = $self.find('.left-side-container');
			const $rightContainer = $self.find('.right-side-container');
			const $sliderBtn = $self.find('.slider-button');

			//on page load
			setContainerWidth($leftContainer);
			setContainerWidth($rightContainer);

			$slider.on('input change', (e) => {
				const sliderPos = e.target.value;
				$rightContainer.css('width', `${sliderPos}%`);
				$sliderBtn.css('left', `calc(${sliderPos}% - 12.5px)`);
			});

			$(window).on('resize', () => {
				setContainerWidth($leftContainer);
				setContainerWidth($rightContainer);
			});
        });
	}

	$(() => {
		$('.weave-slider-module').weaveSlider();
	});
})($);