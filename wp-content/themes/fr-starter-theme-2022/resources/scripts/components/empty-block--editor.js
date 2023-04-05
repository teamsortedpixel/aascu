(function($) {
	const blocksToApplyTo = [
		{
			name: 'acf/tab-panel',
			slug: 'tab-panel'
		},
		{
			name: 'acf/content-tile',
			slug: 'content-tile'
		}
	];

	const onViewRefreshed = ($block, atts) => {
		const clientId = $.fn.frGuten.getClientId($block);
		const block =  $.fn.frGuten.getBlocksByClientId(clientId)[0];

		if(!$block.hasClass('fr--initialized')){
			$(window).on('fr:inner-blocks-toggle-empty-state', (ev, currClientId, innerBlocksLength) => {
				if(clientId === currClientId){
					$block.toggleClass('fr--innerblocks-empty', innerBlocksLength == 0);
				}
			});

			$(window).trigger('fr:inner-blocks-toggle-empty-state', [block.clientId, block.innerBlocks.length]);

			$block.addClass('fr--initialized');
		}
	}

	const onEditorRefresh = () => {
		const editorContent = $.fn.frGuten.getBlocks();

		blocksToApplyTo.forEach(block => {
			const blocks = $.fn.frGuten.getBlocksByName([], editorContent, block.name);
	
			if(blocks.length){
				blocks.forEach(el => {
					$(window).trigger('fr:inner-blocks-toggle-empty-state', [el.clientId, el.innerBlocks.length]);
				});
			}
		});
	}

	wp.domReady(() => {
		if(window.acf){
			blocksToApplyTo.forEach(block => {
				acf.addAction('render_block_preview/type='+block.slug, onViewRefreshed);
			});
		}
	
		wp.data.subscribe(() => {
			onEditorRefresh();
		});
	});
})(jQuery);