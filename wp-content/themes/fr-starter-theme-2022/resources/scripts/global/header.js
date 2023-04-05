import { Collapse } from 'bootstrap';

(function ($) {

    const updateDropdownTrigger = ($navLinks, breakpoint) => {
        if(['xs', 'sm', 'md'].includes(breakpoint)){
            $navLinks.removeAttr("data-bs-hover");
            $navLinks.attr("data-bs-toggle", "dropdown");
        }else{
            $navLinks.removeAttr("data-bs-toggle");
            $navLinks.attr("data-bs-hover", "dropdown");
        }
    }

    const resetMenuItems = ($items) => {
        $.each($items, (i, el) => { 
            $(el).removeClass('show');
            $(el).find('.dropdown-menu').removeClass('show');
        });
    }

    const showDropdown = ($el, closeSiblings = false) => {
        $el.addClass('show');
        $el.find('.dropdown-menu').addClass('show');

        if(closeSiblings){
            const $siblings = $el.parent().find(".menu-item.dropdown, .menu-item.fr-has-mm").not($el);

            $siblings.removeClass('show');
            $.each($siblings, (i, el) => { 
                $(el).find('.dropdown-menu').removeClass('show');
            });
        }
    }

    const hideDropdown = ($el) => {
        $el.removeClass('show');
        $el.find('.dropdown-menu').removeClass('show');
    }

    const addMobileNavSeparator = ($self) => {
        if(!$self.addedSeparator){
            const $firstAccentEl = $self.find('.navbar-nav .menu-item.accent-nav-item').eq(0);
            if($firstAccentEl.length){
                $('<span class="mob-sep show-on-mobile"><b></b></span>').insertBefore($firstAccentEl);
            }

            $self.addedSeparator = true;
        }
    }

	$.fn.frHeader = function () {
		return this.each((i, el) => {
			const $self = $(el);
            const $menuContent = $('#headerMenuContent');
            const collapse = new Collapse($menuContent[0], {
                toggle: false
            });
            $self.stickyHeaderLastScrollTop = 0;
            $self.openedMegaMenuPanelParent = false;
            $self.currentBreakpoint = window.currentBreakpoint();
            $self.addedSeparator = false;

            $self.on('fr:trigger-close-menu', (ev) => {
                collapse.hide();
            });

            $menuContent.on('hidden.bs.collapse', (ev) => {
                $(window).trigger('fr:reset-menu-items');
                $self.removeClass('is--opened is--opening is--closing');
            });

            $menuContent.on('hide.bs.collapse', (ev) => {
                $self.removeClass('is--opened').addClass('is--closing');
            });
            
            $menuContent.on('shown.bs.collapse', (ev) => {
                $self.removeClass('is--opening').addClass('is--opened');
            });
            
            $menuContent.on('show.bs.collapse', (ev) => {
                $self.addClass('is--opening');
            });

            $(window).on('resize', () => {
                const newBreakpoint = window.currentBreakpoint();

                if(newBreakpoint !== $self.currentBreakpoint){
                    $self.currentBreakpoint = newBreakpoint;

                    updateDropdownTrigger($menuContent.find(".nav-link.dropdown-toggle"), $self.currentBreakpoint);

                    if( !['xs', 'sm', 'md'].includes($self.currentBreakpoint) ){
                        $self.trigger('fr:trigger-close-menu');
                        $self.removeClass('is--opened is--opening is--closing');
                    }else{
                        addMobileNavSeparator($self);
                    }

                }
            });

            $menuContent.find(".menu-item.dropdown").on({
                mouseenter: function(){
                    const $el = $(this);
                    if(!['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
                        showDropdown($el, true);
                        $self.openedMegaMenuPanelParent = $el.hasClass('fr-has-mm') ? $el : false;
                    }
                },
                mouseleave: function(){
                    const $el = $(this);
                    if(!['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
                        setTimeout(() => {
                            if(!$self.openedMegaMenuPanelParent){
                                hideDropdown($el);
                                $self.openedMegaMenuPanelParent = false;
                            }
                        }, 300);
                    }
                }
            });

            $(window).on('fr:reset-menu-items', () => {
                resetMenuItems($menuContent.find(".menu-item.dropdown"));
            });

            //on page load
            updateDropdownTrigger($menuContent.find(".nav-link.dropdown-toggle"), $self.currentBreakpoint);
            if( ['xs', 'sm', 'md'].includes($self.currentBreakpoint) ){
                addMobileNavSeparator($self);
            }

		});
	}

	$(() => {
		$('header.fr-header').frHeader();
	});
})($);