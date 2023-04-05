(function ($) {
	$.fn.tabPanels = function () {
		return this.each((i, el) => {
			const $self = $(el);
            const id = $self.attr('fr-module-id');
            const $panels = $self.find('> .tab-content > .tab-panel');

            //on page load
            $panels.eq(0).addClass('active show');
            $(`style#tab-panels-module-${id}`).remove();
        });
	}

	$(() => {
		$('.tab-panels-module').tabPanels();
	});
})($);