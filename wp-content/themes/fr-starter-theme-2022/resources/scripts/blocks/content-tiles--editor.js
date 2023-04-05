(function($) {
    const instances = [];
    const slug = 'fr-page-builder-module-content-tiles';

    const getHowManyTilesNeeded = (layoutOption) => {
        let result = 0;
        switch (layoutOption) {
            case 'none':
                result = 0;
                break;
            case 'layout_1':
                result = 2;
                break;
            case 'layout_2':
                result = 3;
                break;
            case 'layout_3':
                result = 4;
                break;
            case 'layout_4':
                result = 2;
                break;
            case 'layout_5':
                result = 3;
                break;
            default:
                break;
        }

        return result;
    }

    const updateContainerClass = ($block, newVal) => {
        const $container = $($block).closestChild('.module.content-tiles-module');
        $container.removeClass('layout-layout_none layout-layout_1 layout-layout_2 layout-layout_3 layout-layout_4 layout-layout_5').addClass(`layout-${newVal}`);
    }

    const removeBlockDeleting = (clientId) => {
        const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
		const data = {...block.attributes.lock, remove: false};

		$.fn.frGuten.updateBlockAttributes(clientId, {
			lock: data
		});
    }
 
    const updateSelectedOption = (value, clientId) => {
        const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
		const data = {
            ...block.attributes.data,
            layout: value,
            _layout: 'field_content_tiles_layout',
            field_content_tiles_layout: value
        };

		$.fn.frGuten.updateBlockAttributes(clientId, {
			data: data
		});
    }

    const updateInnerBlocks = (clientId, selectedOption) => {
        let block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
        const innerBlocks = block.innerBlocks;

        if(!innerBlocks) return;

        const howManyNeeded = getHowManyTilesNeeded(selectedOption);
        const howManyHas = innerBlocks.length;
        let howManyToInsert = howManyNeeded > howManyHas ? (howManyNeeded - howManyHas) : 0;
        let howManyToRemove = howManyNeeded >= howManyHas ? 0 : Math.abs(howManyHas - howManyNeeded);

        if(howManyNeeded == 0){
            //show layout menu again
            howManyToRemove = 0;
            howManyToInsert = 0;
        }

		//remove blocks as needed
		for (let index = 0; index < howManyToRemove; index++) {
            removeBlockDeleting(innerBlocks[innerBlocks.length - index - 1].clientId);
			wp.data.dispatch('core/editor').removeBlock(innerBlocks[innerBlocks.length - index - 1].clientId);
		}

        //re-query block
        block = $.fn.frGuten.getBlocksByClientId(clientId)[0];

        //add blocks as needed
		for (let index = 0; index < howManyToInsert; index++) {
			const newBlock = wp.blocks.createBlock('acf/content-tile', {
                'lock': {
                    move : false,
                    remove : true
                }
            }, []);
			wp.data.dispatch('core/editor').insertBlocks(newBlock, block.innerBlocks.length, clientId);
		}
    }

    const updateFlipLayoutValue = (clientId, isChecked) => {
        const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
		const data = {
            ...block.attributes.data,
            flip_layout: isChecked,
            field_content_tiles_flip_layout: isChecked,
            _flip_layout: 'field_content_tiles_flip_layout'
        };

		$.fn.frGuten.updateBlockAttributes(clientId, {
			data: data
		});
    }

    const onViewRefresh = ($block, atts) => {
        const clientId = $.fn.frGuten.getClientId($block);
        const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];
        console.log('block', block);

        if(!$block.hasClass('fr--is-initialized')){
            const $choices = $block.closestChild('.fr-content-tiles-layout .layout-options').find('.layout-opt > input');

            $choices.on('change', (ev) => {
                const selected = $(ev.currentTarget).val();
                updateSelectedOption(selected, clientId);
                updateInnerBlocks(clientId, selected);
                updateContainerClass($block, selected);
            });

            $(`button[id="block_${clientId}-change-layout"]`).on('click', () => {
                $choices.eq(0).trigger('click');
            });

            //set default value of flip layout
            if(block.attributes.data && block.attributes.data.flip_layout){
                $(`input#block_${clientId}-flip-layout`).prop('checked', true);
            }

            $(`input#block_${clientId}-flip-layout`).on('change', (ev) => {
                updateFlipLayoutValue(clientId, $(ev.currentTarget).is(':checked') ? 1 : 0);
            });

            $block.addClass('fr--is-initialized');
        }
    }

    wp.domReady(() => {
        if(window.acf){
            acf.addAction(`render_block_preview/type=${slug}`, onViewRefresh); 
        }
		wp.data.subscribe(() => {
		});
	});

})(jQuery);