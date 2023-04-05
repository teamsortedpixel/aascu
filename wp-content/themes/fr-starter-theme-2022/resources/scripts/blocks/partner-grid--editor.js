import './partner-grid';

(function($) {
    const slug = 'fr-page-builder-module-partner-grid';

    const onViewRefresh = ($block, atts) => {
        if(!$block.hasClass('fr--is-initialized')){
            $('.partner-grid-module', $block).partnerGrid();
            $block.addClass('fr--is-initialized');
        }
    }

	wp.domReady(() => {
        if(window.acf){
            acf.addAction(`render_block_preview/type=${slug}`, onViewRefresh);    
        }
    });

})(jQuery);