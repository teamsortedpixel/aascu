(function($) {
    if(acf){
        acf.addAction('render_block_preview/type=fr-page-builder', function($block){
            const clientId = $.fn.frGuten.getClientId($block);
            const block =  $.fn.frGuten.getBlocksByClientId(clientId)[0];

            if(!$block.hasClass('fr--is-initialized')){
                $(window).on('fr:page-builder-show-empty-state', function(e, val){
                    $($block[0]).find('[fr-empty-block-inner-blocks-state]').css('display', val ? '' : 'none');
                });

                $(window).on('fr:add-appender-custom-class', () => {
                    const $button = $block.find('.block-editor-block-list__layout').eq(0);
                    $button.addClass('fr-custom-appender--layout-column fr-custom-appender--page-builder');
                });

                $(window).trigger('fr:page-builder-show-empty-state', [block.clientId, block.innerBlocks.length]);
                $(window).trigger('fr:add-appender-custom-class', [block.clientId, block.innerBlocks.length]);

                $block.addClass('fr--is-initialized');
            }


        });
    }

    //add a message for showing an empty state on the page builder module
    wp.data.subscribe(() => {
		const blocks = $.fn.frGuten.getBlocksByName([], $.fn.frGuten.getBlocks(), 'acf/fr-page-builder');

        blocks.forEach(el => {
            $(window).trigger('fr:page-builder-show-empty-state', el.innerBlocks.length == 0);
            $(window).trigger('fr:add-appender-custom-class', [el.clientId, el.innerBlocks.length]);




        });
    });
    
})(jQuery);