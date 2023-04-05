(function($) {
	const onLayoutColumnRefreshed = ($block, atts) => {
		const clientId = $.fn.frGuten.getClientId($block);
		const block =  $.fn.frGuten.getBlocksByClientId(clientId)[0];

		if(!$block.hasClass('fr--initialized')){
			$(window).on('fr:inner-blocks-toggle-empty-state', (ev, currClientId, innerBlocksLength) => {
				if(clientId === currClientId){
					$block.toggleClass('fr--innerblocks-empty', innerBlocksLength == 0);
				}
			});

			$(window).on('fr:add-appender-custom-class', () => {
				const $button = $block.find('.block-editor-block-list__layout').eq(0);
				$button.addClass('fr-custom-appender--layout-column');
			});

			$(window).trigger('fr:inner-blocks-toggle-empty-state', [block.clientId, block.innerBlocks.length]);
			$(window).trigger('fr:add-appender-custom-class', [block.clientId, block.innerBlocks.length]);

			$block.addClass('fr--initialized');
		}
	}

	const onEditorRefresh = () => {
		const editorContent = $.fn.frGuten.getBlocks();
		const frLayoutColumnBlocks = $.fn.frGuten.getBlocksByName([], editorContent, 'acf/fr-layout-column');

		if(frLayoutColumnBlocks.length){
			frLayoutColumnBlocks.forEach(el => {
				$(window).trigger('fr:inner-blocks-toggle-empty-state', [el.clientId, el.innerBlocks.length]);
				$(window).trigger('fr:add-appender-custom-class', [el.clientId, el.innerBlocks.length]);
			});
		}
	}

	wp.domReady(() => {
		if(window.acf){
			acf.addAction('render_block_preview/type=fr-layout-column', onLayoutColumnRefreshed);
		}
	
		wp.data.subscribe(() => {
			onEditorRefresh();
		});
	});
})(jQuery);