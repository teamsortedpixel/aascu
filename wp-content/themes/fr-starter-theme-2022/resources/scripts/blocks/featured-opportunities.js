(function ($) {
	$.fn.featuredOpp = function () {
		return this.each((i, el) => {
			const $self = $(el);
            const $imagePanels = $self.find('.tab-content-images > .tab-pane');
            const $tabs = $self.find('.featured-opp-nav-tabs');

            $tabs.on('show.bs.tab', (el) => {
                const $activeBtn = $(el.target);
                const activeImagePanelId = $activeBtn.attr('aria-controls') + '-image';

                const $activeImagePanel = $imagePanels.filter((i, panel) => {
                    return $(panel).attr('id') === activeImagePanelId;
                });

                if($activeImagePanel.length){
                    $activeImagePanel.addClass('active');
                    setTimeout(() => {
                        $activeImagePanel.addClass('show');
                    }, 150);

                    $imagePanels.not($activeImagePanel).removeClass('show active');
                }
            });
		});
	}

	$(() => {
		$('.featured-opportunities-module, .featured-resources-module').featuredOpp();
	});
})(jQuery);