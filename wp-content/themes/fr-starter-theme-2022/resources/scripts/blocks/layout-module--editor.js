(function($) {
	const setAcfLayoutOption = (clientId, newValue) => {
		const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
		const data = {...block.attributes.data, field_fr_layout_layouts : newValue};

		$.fn.frGuten.updateBlockAttributes(clientId, {
			data: data
		});

		$(window).trigger('fr:update-acf-layout-option', [clientId, newValue]);
	}

	const updateAcfUI = ($options, value) => {
		const $selected = $options.filter((i, el) => {
			return $(el).val() == value;
		});
		$selected.parent().addClass('selected');
		$options.parent().not($selected.parent()).removeClass('selected');
	}

	const onAcfFieldInitialized = (field) => {
		if(field.$el.length && !field.$el.frIsInitialized){
			const acfFieldBlockId = $(field.$el).find('> .acf-input > input[type="hidden"]').attr('name').split('[')[0].replace('acf-', '');
			const $options = $(field.$el).find('> .acf-input > .acf-button-group input[type="radio"]');

			$(window).on('fr:update-acf-layout-option', (ev, clientId, newValue) => {
				if(!newValue) return;

				if(clientId == acfFieldBlockId){
					field.val(newValue);
				}
			});

			field.on('change', (e) => {
				//HACK: UI does not update, update manually
				updateAcfUI($options, field.val());
				
				//Update innerblocks
				updateInnerBlocks(acfFieldBlockId, field.val());
			});

			field.$el.frIsInitialized = true;
		}
	}

	const onModuleViewRefresh = ($block, atts) => {
		const $layoutSelector = $($block).find('[fr-column-inserter]');
		const clientId = $.fn.frGuten.getClientId($block);
		const $layoutSelectorOptions = $layoutSelector.find('.btn-group input');
		const block =  $.fn.frGuten.getBlocksByClientId(clientId)[0];

		//Initialize events
		if(!$block.hasClass('fr--is-initialized')){
			$layoutSelectorOptions.on('change', (ev) => {
				const selected = $(ev.currentTarget).val();

				setAcfLayoutOption(block.clientId, selected);

				updateInnerBlocks(block.clientId, selected);

				console.log('$block', $block);

				//fix bug
				setTimeout(() => {
					$block.find('>.acf-block-preview > .wp-block-fr-page-builder-module-layout > .container-fluid > .row > .block-editor-inner-blocks > .block-editor-block-list__layout > .wp-block-acf-fr-layout-column').css('transform', 'none');
				}, 300);
			});

			$block.addClass('fr--is-initialized');
		}
	}

	const  updateInnerBlocks = (clientId, layoutVal) => {
		let foundObj = $.fn.frGuten.getBlocksByClientId(clientId)[0];
		let columnCount = 0;
		let howManyToInsert = 0;
		let howManyHas = foundObj.innerBlocks ? foundObj.innerBlocks.length : 0;

		switch (layoutVal) {
			case '1_1':
				columnCount = 1;
				break;
			case '13_13_13':
			case '16_46_16':
				columnCount = 3;
				break;
			case '13_23':
			case '12_12':
			case '23_13':   
				columnCount = 2;
				break;
			default:
				break;
		}

		howManyToRemove = columnCount >= howManyHas ? 0 : Math.abs(howManyHas - columnCount);
		howManyToInsert = columnCount > howManyHas ? (columnCount - howManyHas) : 0;

		//remove blocks as needed
		for (let index = 0; index < howManyToRemove; index++) {
			wp.data.dispatch('core/editor').removeBlock(foundObj.innerBlocks[foundObj.innerBlocks.length - index - 1].clientId);
		}

		//re-query the object
		foundObj = $.fn.frGuten.getBlocksByClientId(clientId)[0];

		//then add the columns
		for (let index = 0; index < howManyToInsert; index++) {
			const columnBlock = wp.blocks.createBlock('acf/fr-layout-column', {}, []);
			wp.data.dispatch('core/editor').insertBlocks(columnBlock, foundObj.innerBlocks.length, clientId);
		}
	}

	//ACF initialization
	if(acf){
		acf.addAction('render_block_preview/type=fr-page-builder-module-layout', onModuleViewRefresh);

		acf.addAction('append', function(){
			const fields = acf.getFields({
				name: 'layouts'
			});

			fields.forEach(function(field) {
				onAcfFieldInitialized(field);
			});
		});
	}

	//add a message for showing an empty state on the page builder module
	let initialLayoutModulesState = {};
	wp.data.subscribe(() => {
		const frLayoutBlocks = $.fn.frGuten.getBlocksByName([], $.fn.frGuten.getBlocks(), 'acf/fr-page-builder-module-layout');

		frLayoutBlocks.forEach(el => {
			const oldLayoutBlockState = $.fn.frGuten.getBlockByClientId(initialLayoutModulesState, el.clientId);
			const { data } =  el.attributes;

			if(oldLayoutBlockState && oldLayoutBlockState.innerBlocks.length !== el.innerBlocks.length){
				if(el.innerBlocks.length === 0 && data.field_fr_layout_layouts !== 'None'){
					setAcfLayoutOption(el.clientId, 'None');
				}
			}
			
		});

		initialLayoutModulesState = frLayoutBlocks;
	});
	
})(jQuery);