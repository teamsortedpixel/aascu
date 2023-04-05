(function($) {
	const blocksToApplyTo = [
		{
			name: 'acf/content-tile',
			slug: 'content-tile'
		}
	];

	const onViewRefreshed = ($block, atts) => {
		const clientId = $.fn.frGuten.getClientId($block);
		const block =  $.fn.frGuten.getBlocksByClientId(clientId)[0];

		if(!$block.hasClass('fr--only-one-initialized')){
			$(window).on('fr:inner-blocks-is-more-than-one', (ev, currClientId, innerBlocksLength) => {
				if(clientId === currClientId){
					$block.closestChild('.block-editor-inner-blocks').toggleClass('fr--disable-appender', innerBlocksLength != 0);
					if(innerBlocksLength){
						$block.addClass('fr--has-content');
					}else{
						$block.removeClass('fr--has-content');
					}
				}
			});

			$(window).trigger('fr:inner-blocks-is-more-than-one', [block.clientId, block.innerBlocks.length]);

			$block.addClass('fr--only-one-initialized');
		}
	}

	const onEditorRefresh = () => {
		const editorContent = $.fn.frGuten.getBlocks();

		blocksToApplyTo.forEach(block => {
			const blocks = $.fn.frGuten.getBlocksByName([], editorContent, block.name);
	
			if(blocks.length){
				blocks.forEach(el => {
					$(window).trigger('fr:inner-blocks-is-more-than-one', [el.clientId, el.innerBlocks.length]);
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