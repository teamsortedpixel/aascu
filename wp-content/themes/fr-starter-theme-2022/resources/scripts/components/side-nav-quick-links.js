(function ($) {
	$.fn.sideNavQuickLinks = function () {

        const onClickOutsideSideNav = (ev, $self) => {
            if(!$(ev.target).closest('.side-bar-nav-links-container').length && $self.hasClass('is--opened')){
                $self.removeClass('is--opened');
            }
        }

        const getFirstAnchorTargetOnThePage = ($self) => {
            let $result = false;
            let lowestOffset = $('body').height();
            const $list = $self.find('.list-container');

            $.each($list.find('a[href]'), (i, el) => { 
                const $el = $($(el).attr('href'));
                if($el.length && $el.offset().top <= lowestOffset){
                    lowestOffset = $el.offset().top;
                    $result = $el;
                }
            });

            return $result;
        }

        const enableDisableScrollSpy = ($self, action = 'enable') => {
            if(action == 'enable'){
                const $items = $self.find('.list-container a[href]');
                const $firstElement = getFirstAnchorTargetOnThePage($self);

                if($items.length){
                    $(window).on('scroll.fr-side-nav', (ev) => {
                        const scrollY = window.scrollY + $self.topTriggerOffset;

                        if(scrollY < $firstElement.offset().top){
                            if($items.filter('.active').length){
                                $items.removeClass('active');
                            }
                        }else{                            
                            $.each($items, (i, el) => { 
                                const $el = $(el);
        
                                if(scrollY >= $($el.attr('href')).offset().top){
                                    $items.not($el).removeClass('active');
                                    $el.addClass('active');
                                    return;
                                }
                            });
                        }
                    });

                    //On panel opened
                    $(window).trigger('scroll.fr-side-nav');
                }
            }else{
                $(window).off('.fr-side-nav');
            }
        }

        const enableDisableClickEvents = ($self, action = 'enable') => {
            const $navItems = $self.find('.list-container a[href]');
            if(action == 'enable'){
                $navItems.on('click.fr-side-nav', (ev) => {
                    ev.preventDefault();
                    const $target = $($(ev.currentTarget).attr('href'));

                    if($target.length){
                        window.scroll({
                            top: $target.offset().top - $self.topTriggerOffset + 1
                        });
                    }
                });
            }else{
                $navItems.off('.fr-side-nav');
            }
        }

        const openCloseSideNav = ($self, action = 'open') => {
            $self.toggleClass('is--opened', action == 'open');

            //Click Event
            if(action == 'open'){
                $(document).on('click.fr-side-nav', (ev) => {
                    onClickOutsideSideNav(ev, $self);
                });
            }else{
                $(document).off('.fr-side-nav');
            }

            //ScrollSpy Event
            enableDisableScrollSpy($self, action == 'open' ? 'enable' : 'disable');

            //Click Event
            enableDisableClickEvents($self, action == 'open' ? 'enable' : 'disable');
        }

		return this.each((i, el) => {
			const $self = $(el);
            const $buttons = $self.find('.toggle-btn');
            const $listContainer = $self.find('.list-container');

            //props
            $self.currentBreakpoint = false;
            $self.topTriggerOffset = 85; //How many pixels to offset from the top of the active element so that the anchoring triggers
            
            $buttons.on('click', (ev) => {
                ev.preventDefault();
                openCloseSideNav($self, !$self.hasClass('is--opened') ? 'open' : 'close');
            });

            // center list with respect to the main button toggler
            $listContainer.css('margin-top', '-' + (($listContainer.height() - 60) / 2) + 'px');

            //After figure out the position, then show it
            $self.addClass('is--visible');

            $(window).on('resize', () => {
                const newBreakpoint = window.currentBreakpoint();

                if(newBreakpoint !== $self.currentBreakpoint){
                    if(['xs', 'sm', 'md'].includes(newBreakpoint)){
                        openCloseSideNav($self, 'close');
                    }

                    $self.currentBreakpoint = newBreakpoint;
                }
            });
		});
	}

	$(() => {
		$('.side-bar-nav-links-container').sideNavQuickLinks();
	});
})($);