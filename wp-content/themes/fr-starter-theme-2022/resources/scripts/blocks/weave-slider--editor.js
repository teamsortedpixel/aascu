(function($) {
    const slug = 'fr-page-builder-module-weave-slider';

    const onViewRefresh = ($block, atts) => {
        if(!$block.hasClass('fr--is-initialized')){

            $(window).on('resize fr:weave-initialize', () => {
                const $container = $block.closestChild('.weave-slider-module'); 
                const $rightSide = $container.find('.right-side-container');
                $rightSide.find('.side-inner').css('width', `${$rightSide.closest('.weave-slider-inner').width()}px`);
            });

            $(window).trigger('fr:weave-initialize');

            $block.addClass('fr--is-initialized');
        }
    }

	wp.domReady(() => {
        if(window.acf){
            acf.addAction(`render_block_preview/type=${slug}`, onViewRefresh);    
        }
    });

})(jQuery);