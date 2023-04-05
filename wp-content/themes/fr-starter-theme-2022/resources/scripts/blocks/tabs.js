(function ($) {

	const getNavBarOffset = () => {
		let result = 0;
		const $header = $('header.banner');

		if($header.length && !$header.hasClass('anchor--top')){
			result = !$header.hasClass('up') ? $header.height() : result;
		}

		return result;
	}

	const getScrollTop = () => {
		return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
	}

	const wpAdminBarHeight = () => {
		if(['xs', 'sm', 'md'].includes(window.currentBreakpoint())){
			return 0;
		}else{
			return $('#wpadminbar').length ? $('#wpadminbar').height() : 0;
		}
	}
	const getVerticalOffset = ($self) => {
		return $self.height() + getNavBarOffset() + wpAdminBarHeight();
	}

	const getAnchoredElements = ($tabs) => {
		const $elements = [];

		$.each($tabs, (i, el) => { 
			const itemId = $(el).attr('fr-anchor-id');
			if($(`#${itemId}`).length){
				$elements.push($(`#${itemId}`));
			}
		});

		return $elements;
	}

	const onAnchorReached = ($self, anchoredElements, scrollTop) => {
		let currentIndex = 0;
		const $tabItems = $self.find('> .container-fluid > .tabs > li');

		for (let index = 0; index < anchoredElements.length; index++) {
			const $el = $(anchoredElements[index]);

			if( scrollTop >= $el.offset().top - getVerticalOffset($self) ){
				currentIndex = index;
			}
		}

		$tabItems.not($tabItems.eq(currentIndex).addClass('active')).removeClass('active');
	}

	const toggleTabsSticky = ($self, scrollTop, originalYPos) =>{
		if(scrollTop >= originalYPos){
			$self.addClass('is--fixed');
		} else {
			$self.removeClass('is--fixed');
		}
		
		$self.css('transform', 'translateY('+($self.hasClass('is--fixed') ? getNavBarOffset() : 0)+'px)');
	}

	$.fn.frTabs = function () {
		return this.each((i, el) => {
			const $self = $(el);
			const mode = $self.attr('fr-tabs-mode');
			const $tabItems = $self.find('> .container-fluid > .tabs > li');
			const stickyOnScroll = $self.hasClass('fr-sticky-on-scroll');
			$self.originalYPos = $self.offset().top;
			const $mobileOptionsToggler = $self.find('.mobile-toggle-options');

			if(stickyOnScroll){
				$(window).on('scroll', (ev) => {
					toggleTabsSticky($self, getScrollTop(), $self.originalYPos - wpAdminBarHeight());
				});

				$mobileOptionsToggler.on('click', () => {
					$self.toggleClass('is--opened', !$self.hasClass('is--opened'));
				});

				$tabItems.on('click', (ev) => {
					$self.removeClass('is--opened');
				});
			}

			if(mode === 'anchors'){
				const anchoredElements = getAnchoredElements($tabItems);

				$tabItems.on('click', (ev) => {
					const $el = $(ev.currentTarget);
					const $target = $('#'+$el.attr('fr-anchor-id'));

					if(!$target.length) return;

					//I NEED TO KNOW THE DIRECTION OF THE NEXT TARGET
					const targetDirection = getScrollTop() > $target.offset().top ? 'up' : 'down';
					const targetOffset = $target.offset().top - (targetDirection == 'up' ? ($('header.banner').height()) : 0) - wpAdminBarHeight() - $tabItems.filter('.active').height();

					window.scrollTo({
						top: targetOffset,
						behavior: 'smooth',
					});
				});

				$(window).on('scroll', (ev) => {
					onAnchorReached($self, anchoredElements, getScrollTop());
				});
			}else{
				$tabItems.on('click', (ev) => {
					const $el = $(ev.currentTarget);

					const targetOffset = $($el.attr('data-bs-target')).offset().top - getVerticalOffset($self);

					if(stickyOnScroll && targetOffset < getScrollTop()){
						window.scrollTo({
							top: $self.originalYPos,
							behavior: 'smooth',
						});
					}
				});

				
				//on page load
				$(window).on('load', () => {
					if(location.hash && $tabItems.filter('[data-bs-target="'+location.hash+'-pane"]').length){
						const $anchorItem = $tabItems.filter('[data-bs-target="'+location.hash+'-pane"]');
						$anchorItem.trigger('click');

						if(!stickyOnScroll){
							window.scrollTo({
								top: $anchorItem.offset().top - $('header.rn-header').height(),
								behavior: 'smooth',
							});
						}
					}
				})
			}

			$(window).on('resize', () => {
				$self.removeClass('is--fixed');
				$self.originalYPos = $self.offset().top;
				$self.removeClass('is-fixed');
				//close opened menu on fixed position tabs
				$self.removeClass('is--opened');
			});
		});
	}

	$(() => {
		$('.tabs-module').frTabs();
	});
})($);