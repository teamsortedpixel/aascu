(function($) {

	const checkIfInnerBlocksEmpty = (clientId) => {
		let result = false;

		const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];

		if(block && block.innerBlocks.length) result = true;

		return result;
	}

	const onContentSectionRefreshed = ($block, atts) => {
		const clientId = $.fn.frGuten.getClientId($block);

		if(!$block.hasClass('fr--initialized')){			
			$(window).on('fr:add-appender-custom-class', () => {
				const $button = $block.find('.block-editor-block-list__layout').eq(0);
				$button.addClass('fr-custom-appender--content-section');
			});

			$(window).on('fr:inner-blocks-toggle-empty-state', (ev, currBlockId, innerBlocksLength) => {
				if(clientId === currBlockId){
					$block.toggleClass('fr--innerblocks-empty', !innerBlocksLength);
				}
			});

			$block.closestChild('a[fr-open-sidebar-btn]').on('click', () => {
				const isSidebarOpened = wp.data.select('core/edit-post').isEditorSidebarOpened('edit-post/document');
				if (!isSidebarOpened) {
					wp.data.dispatch('core/edit-post').openGeneralSidebar('edit-post/document');
				}
			});
			
			$block.addClass('fr--initialized');
		}

		//ALSO NEEDS TO CHECK ON EVERY RENDER
		const isInnerBlocksEmpty = checkIfInnerBlocksEmpty(clientId);
		$(window).trigger('fr:add-appender-custom-class', [clientId, isInnerBlocksEmpty]);
		$(window).trigger('fr:inner-blocks-toggle-empty-state', [clientId, isInnerBlocksEmpty]);
	}

	const onEditorRefresh = () => {
		const editorContent = $.fn.frGuten.getBlocks();
		const contentSectionBlocks = $.fn.frGuten.getBlocksByName([], editorContent, 'acf/block-container');

		if(contentSectionBlocks.length){
			contentSectionBlocks.forEach(el => {
                $(window).trigger('fr:inner-blocks-toggle-empty-state', [el.clientId, el.innerBlocks.length]);
				$(window).trigger('fr:add-appender-custom-class', [el.clientId, el.innerBlocks.length]);
			});
		}
	}

	wp.domReady(() => {
		if(window.acf){
			acf.addAction('render_block_preview/type=block-container', onContentSectionRefreshed);
		}
	
		wp.data.subscribe(() => {
			onEditorRefresh();
		});
	});
})(jQuery);