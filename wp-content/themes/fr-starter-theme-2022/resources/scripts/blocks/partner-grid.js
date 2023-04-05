(function ($) {
	$.fn.partnerGrid = function () {
        const updateCardElementHeight = ($items, selector, isImage) => {
            //group elements per line their at, using the position top value
            const groups = {};

            console.log('selector', selector);

            $.each($items, (i, el) => {
                const $el = $(el);
                const pos = $el.position().top;

                //reset values
                $el.find(selector).css('height', '');

                if(typeof groups[pos] == 'undefined'){
                    groups[pos] = [];
                }
                groups[pos].push($el);
            });

            //for each row
            $.each(groups, (row, elements) => {
                let tallest = 0;

                //for each element of line
                $.each(elements, (i, el) => {
                    const $el = $(el);
                    let height = $el.find(selector).height();

                    if(isImage){
                        height = $($el.find(selector).find('>img')).height();
                    }

                    if(height > tallest){
                        tallest = height;
                    }               
                });

                //let's add these values to all the cards in the row
                $.each(elements, (i, el) => {
                    const $el = $(el);
                    $el.find(selector).css('height', tallest + 'px');
                });
            });
        }

        const onWindowResize = ($self) => {
            const $gridContainer = $self.find('.partner-grid-wrapper');
            const $items = $gridContainer.find('.logo-group');

            updateCardElementHeight($gridContainer.find('.logo-group'), '.logo-group-title');
            updateCardElementHeight($gridContainer.find('.logo-item'), '> a', true);
        }

		return this.each((i, el) => {
			const $self = $(el);
            const $wrapper = $self.find('.partner-grid-wrapper');

            if($wrapper.hasClass('style-1')) return;
            
			//vars
            $self.resizeDebounce = false;

            //add event on resize
			$(window).on('resize fr:pseudo-resize', () => {
                clearTimeout($self.resizeDebounce);
                $self.resizeDebounce = setTimeout(() => {
                    onWindowResize($self);
                }, 250);
            });

            //on page load
            $(window).trigger('fr:pseudo-resize');
		});
	}

	$(() => {
		$('.partner-grid-module').partnerGrid();
	});
})(jQuery);