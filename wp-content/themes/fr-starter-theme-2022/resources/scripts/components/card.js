(function ($) {
	$.fn.frCard = function () {

		/**
		 * If the element does not have a value on the fr-truncate-lines property
		 * let's use this to configure it based off designs. THIS SHOULD BE DEPRECATED
		 * AT SOME POINT, KIND OF DIFFICULT TO MAINTAIN
		 * 
		 * @param {*} $self 
		 * @param {*} $el 
		 * @returns 
		 */
		const getTruncationLineConfig = ($self, $el) => {
			let result = 1;

			if($self.hasClass('type-page')){
				if($el.parent().hasClass('title-wrapper')){
					result = 3;
				}

				if($el.parent().hasClass('desc')){
					result = 4;
				}
			}

			if($self.hasClass('type-news')){
				if($el.parent().hasClass('title-wrapper')){
					result = 4;

					if($self.hasClass('has-image') || $self.hasClass('condensed')){
						result = 3;
					}
				}

				if($el.parent().hasClass('desc')){
					result = 4;
				}
			}

			if($self.hasClass('type-resource')){
				if($el.parent().hasClass('title-wrapper')){
					result = 4;
					if($self.hasClass('condensed')){
						result = $self.hasClass('has-icon') ? 3 : 4;
					}
				}

				if($el.hasClass('author')){
					result = 2;
				}
			}

			if($self.hasClass('type-event')){
				if($el.parent().hasClass('title-wrapper')){
					result = $self.hasClass('card-type-regular') ? 4 : 3;
				}

				if($el.parent().hasClass('desc')){
					result = 5;
				}
			}

			return result;
		}

		const truncateText = ($element, lines) => {
			//Set original text first
			if($element.frOriginalText){
				$element.text($element.frOriginalText);
			}
			
			const elHeight = $element.height();
			const lineHeight = parseFloat($element.css('line-height'));

			if(elHeight / lineHeight > lines){
				$(window).trigger('fr:truncate-text', [$element, Math.ceil(lineHeight * lines), false, function(){
					//remove the class that avoids flash of styles
					$element.parent().removeClass('not--truncated');
				}]);
			}else{
				$element.parent().removeClass('not--truncated');
			}
		}

		const setUpTruncateElements = (elements) => {
			$.each(elements, (i, el) =>  { 
				const $el = el.$el;
				const lines = el.lines;
				const tooltip = el.tooltip;
				let resizeDebounce = false;

				//save original value 
				$el.frOriginalText = $el.text();

				//do truncation
				truncateText($el, lines);

				//add tooltip if needed
				if(tooltip && tooltip.length){
					$el.tooltip({ 
						placement: 'auto',
						title: () => {
							return tooltip;
						}
					});
				}

				//add event on resize
				$(window).on('resize', () => {
					clearTimeout(resizeDebounce);
					resizeDebounce = setTimeout(() => {
						truncateText($el, lines);
					}, 400);
				});
			});
		}

		const initializeTruncation = ($self) => {
			const truncatableElements = [];
			$.each($self.find('[fr-truncate-lines]'), (i, el) => { 
				const $el = $(el);
				
				truncatableElements.push({
					$el: $el,
					lines: parseInt($el.attr('fr-truncate-lines') || getTruncationLineConfig($self, $el)),
					tooltip: $el.attr('data-title')
				})
			});

			setUpTruncateElements(truncatableElements);
		}

		return this.each((i, el) => {
			const $self = $(el);

			if(!window.frFontsLoaded){
				$(window).on('fr:fonts-loaded', () => {
					initializeTruncation($self);
				});
			}else{
				initializeTruncation($self);
			}
        });
	}

	$.initialize('.fr-card', function() {
		$(this).frCard();
	});
})(jQuery);