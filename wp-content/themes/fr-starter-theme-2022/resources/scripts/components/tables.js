(function ($) {
	$.fn.wysiwygTables = function () {

        const toggleMobileContainer = ($table, shouldAdd) => {
            if(shouldAdd){
                if(!$table.parent().hasClass('fr-mobile-table-container')){
                    $($table).wrap('<div class="fr-mobile-table-container"></div>');
                }
            }else{
                if($table.parent().hasClass('fr-mobile-table-container')){
                    $($table).unwrap();
                }
            }
        } 

		return this.each((i, el) => {
			const $self = $(el);
            //vars
			$self.currentBreakpoint = false;

			$(window).on('resize fr:pseudo-resize', () => {
                const newBreakpoint = window.currentBreakpoint();

                if(newBreakpoint !== $self.currentBreakpoint){
                    toggleMobileContainer($self, ['xs', 'sm', 'md'].includes(newBreakpoint));
                    $self.currentBreakpoint = newBreakpoint;
                }
            });

            $(window).trigger('fr:pseudo-resize');
        });
	}

	$(() => {
        $('.wysiwyg-content table').wysiwygTables();
	});
})(jQuery);