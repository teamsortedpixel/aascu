import {isEqual} from '../util/isObjectEqual';

(function($) {
    const instances = [];
    const cacheViewExecution = [];
    const slug = 'fr-page-builder-module-tab-panels';

    //METHODS
    const updateViewUi = (clientId, $block = false, isFirstTime = false, isInitialized = false) => {
        if(!$block){
            $block = $(`[data-block="${clientId}"]`);
        }
		const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];

        if(!$block.length || !block) return;

        const $controls = $block.closestChild('.fr-module-controls');

        populateTabPaginationContainer($block, $controls.find('.tab-pagination-container'), block.innerBlocks || []);

        const appendBlockName =  $controls.attr('fr-append-block-name');
        const $tabPaginationContainer = $controls.find('.tab-pagination-container');
        const $addBtn = $controls.find('.fr-add-tab-panel');
        const $innerBlocksContainer = $block.closestChild('.block-editor-inner-blocks > .block-editor-block-list__layout');

        if(!isFirstTime){
            $tabPaginationContainer.on('click', '> .btn', (ev) => {
                const $el = $(ev.currentTarget);
                const $buttons = $tabPaginationContainer.find('> .btn');
                const $siblings = $buttons.not($el);
                const clientId = $el.attr('fr-client-id');
    
                if(clientId){
                    wp.data.dispatch('core/block-editor').selectBlock(clientId);
    
                    $siblings.removeClass('active');
                    $el.addClass('active');
    
                    updateVisibilityOfSelectedTabPanel(clientId, $innerBlocksContainer);
                }
            });
            
            $controls.on('click','.fr-add-tab-panel', (ev) => {
                ev.preventDefault();
                insertNewPanel(appendBlockName, clientId);
            });
        }

        if(isInitialized){
            if(block.innerBlocks.length){
                updateVisibilityOfSelectedTabPanel(block.innerBlocks[0].clientId, $innerBlocksContainer);
            }
        }
    }

    const getBLockViewInstance = (clientId) => {
        return $(`.wp-block[data-block="${clientId}"]`);
    }

    const createTabIndexItem = (data) => {
        return `<button type="button" class="btn btn-outline-secondary ${data.active ? 'active' : ''}" fr-data-index="${data.index}" fr-client-id="${data.clientId}">${data.index + 1}</button>`;
    }

    const getTabPanelPredecesor = ($el) => {
        const $leaf = $el.closest('.block-editor-list-view-leaf');
        let $prev = $leaf.prev();
        let currLevel = parseInt($leaf.attr('aria-level'));

        while (currLevel > 0) {
            if( (currLevel - 1) == parseInt($prev.attr('aria-level')) ){
                const prevBlock = $.fn.frGuten.getBlockByClientId($.fn.frGuten.getBlocks(), $prev.attr('data-block'));

                if(prevBlock && prevBlock.attributes.name === 'acf/tab-panel'){
                    return prevBlock;
                }
            }
            $prev = $prev.prev();
            currLevel--;
        }

        return false;
    }

    const checkIfIsTabPanel = (clientId) => {
        const block = $.fn.frGuten.getBlockByClientId($.fn.frGuten.getBlocks(), clientId);

        return block && block.attributes.name === 'acf/tab-panel';
    }

    const populateTabPaginationContainer = ($block, $container, innerBlocks) => {
        //empty pagination first
        $container.html('');

        if(innerBlocks.length == 0) return;

        //set active state
        const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();
        const selectedBlockClientId = selectedBlock && selectedBlock.name === `acf/tab-panel` ? selectedBlock.clientId : innerBlocks[0].clientId;

        //add pagination
        innerBlocks.forEach((el, i) => {
            $container.append($(createTabIndexItem({
                index: i,
                id: `ID-${i}`,
                clientId: el.clientId,
                active: selectedBlockClientId == el.clientId
            })));

            if(selectedBlockClientId == el.clientId){
                updateVisibilityOfSelectedTabPanel(el.clientId, $block.closestChild('.block-editor-inner-blocks > .block-editor-block-list__layout'));
            }
        });

    }

    const isClientIdPartOfInnerBlocks = (clientId, innerBlocks) => {
        let result = -1;

        if(innerBlocks && innerBlocks.length){
            innerBlocks.forEach((el, i) => {
                if(el.clientId == clientId){
                    result = i;
                }
            });
        }

        return result;
    }

    const onViewRefresh = ($block, cacheViewExecution) => {
        const clientId = $.fn.frGuten.getClientId($block);
        const block = $.fn.frGuten.getBlocksByClientId(clientId)[0];

        if(!$block.hasClass('fr--is-initialized')){
            $(window).on('fr:update-view-ui', (ev, evClientId) => {
                if(evClientId === clientId){
                    updateViewUi(clientId, $block, true);
                }
            });

            updateViewUi(clientId, $block, false, true);

            $block.closestChild('.fr-module-controls').addClass('fr-controls-initialized');

            //this will make it so that if you click(activate) a tab panel on the tree view it will aslo activate the item on the custom UI we created for Tab Panels Pagination
            $('body').on('click', '.block-editor-list-view-block-select-button.block-editor-list-view-block-contents', (ev) => {
                const $el = $(ev.currentTarget);
                const clientId = $el.attr('href').replace('#block-', '');

                //check if element has a parent that IS a tabPanel block, if so get that ID
                const $predecesor = getTabPanelPredecesor($el);

                const index = $predecesor && $predecesor.clientId ? isClientIdPartOfInnerBlocks($predecesor.clientId, block.innerBlocks) : (checkIfIsTabPanel(clientId) ? isClientIdPartOfInnerBlocks(clientId, block.innerBlocks) : -1);
                
                if(index !== -1){
                    updateVisibilityOfSelectedTabPanel(block.innerBlocks[index].clientId, $block.closestChild('.block-editor-inner-blocks > .block-editor-block-list__layout'));
                    updateSelectedPaginationIndex(block.innerBlocks[index].clientId, $block);
                }
            });

            $block.addClass('fr--is-initialized');
        }

        if(cacheViewExecution.includes(clientId)){
            updateViewUi(clientId, $block, false);
            delete cacheViewExecution[clientId]; 
        }
    }

    const updateVisibilityOfSelectedTabPanel = (clientId, $innerBlocksContainer) => {
        const $panels = $innerBlocksContainer.find('>.wp-block');
        const $selected = $panels.filter(`[id="block-${clientId}"]`);
        
        $panels.not($selected).css('display', 'none');
        $selected.css('display', '');
    }

    const insertNewPanel = (newBlockName, clientId) => {
        const insertIndex = $.fn.frGuten.getBlockByClientId($.fn.frGuten.getBlocks(), clientId).innerBlocks.length;
        const newBlock = wp.blocks.createBlock(newBlockName, {});

        wp.data.dispatch('core/editor').insertBlock(newBlock, insertIndex, clientId);
    }

    const updateSelectedPaginationIndex = (clientId, $block) => {
        const $controls = $block.closestChild('.fr-module-controls');
        const $tabPaginationContainer = $controls.find('.tab-pagination-container');

        $tabPaginationContainer.find('> .btn').not($tabPaginationContainer.find('> .btn').filter(`[fr-client-id="${clientId}"]`).addClass('active')).removeClass('active');

    }

	wp.domReady(() => {

        if(window.acf){
            acf.addAction(`render_block_preview/type=${slug}`, ($block, atts) => {
                onViewRefresh($block, cacheViewExecution);
            });    
        }

        let oldBlockIds = [];
		wp.data.subscribe(() => {
            const currentBlockIds = $.fn.frGuten.__getblockClientIdsByName(`acf/${slug}`) || [];

            if(!isEqual(currentBlockIds, oldBlockIds, true)){
                const excluded = currentBlockIds.filter(x => oldBlockIds.includes(x)) || [];
                currentBlockIds.forEach(id => {
                    if(!instances[id]){
                        instances[id] = {
                            innerBlocks: false
                        };
                    }
                });
    
                excluded.forEach(id => {
                    delete instances[id];
                });
            }

            for (const key in instances) {
                const currentBlock = $.fn.frGuten.getBlocksByClientId(key);
                const currentInnerBlocks = currentBlock.length ? currentBlock[0].innerBlocks.map(({clientId}) => clientId) : [];

                if(!isEqual(currentInnerBlocks, instances[key].innerBlocks, true)){
                    if(!getBLockViewInstance(key).length){
                        cacheViewExecution.push(key);
                    }else{
                        $(window).trigger('fr:update-view-ui', [key]);
                    }

                    instances[key].innerBlocks = currentInnerBlocks;
                }
            }

            oldBlockIds = currentBlockIds;
		});



	});

})(jQuery);