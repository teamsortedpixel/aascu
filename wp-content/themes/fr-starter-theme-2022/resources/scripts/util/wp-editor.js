(function($){
	$.fn.frGuten = {};

	$.fn.frGuten.getClientId = ($block) => {
		return $block.closest('.wp-block').attr('id').replace('block-', '');
	}

	//EXPERIMENTAL
	$.fn.frGuten.__getblockClientIdsByName = (name) => {
		const result = [];
		const blocks = $.fn.frGuten.getBlocksByName([], $.fn.frGuten.getBlocks(), name);

		blocks.forEach(el => {
			result.push(el.clientId);
		});

		return result;
	}

	$.fn.frGuten.getBlocks = () => {
		const editor = wp.data.select('core/editor');
		return editor == null ? [] : editor.getBlocks();
	}

	$.fn.frGuten.getBlockByClientId = (blocks, clientId) => {
		for (el of blocks) {
			if (el.clientId === clientId) return el;
			if (el.innerBlocks) {
				const innerEl = $.fn.frGuten.getBlockByClientId(el.innerBlocks, clientId);
				if (innerEl) return innerEl;
			}
		}
	}

	$.fn.frGuten.getBlockByClientId = (editorBlocArr, clientId) => {
		for (el of editorBlocArr) {
			if (el.clientId === clientId) return el;
			if (el.innerBlocks) {
				const innerEl = $.fn.frGuten.getBlockByClientId(el.innerBlocks, clientId);
				if (innerEl) return innerEl;
			}
		}
	}

	$.fn.frGuten.getBlockByName = (editorBlocArr, name) => {
		for (el of editorBlocArr) {
			if (el.name === name) return el;
			if (el.innerBlocks) {
				const innerEl = $.fn.frGuten.getBlockByName(el.innerBlocks, name);
				if (innerEl) return innerEl;
			}
		}
	}

	$.fn.frGuten.getBlocksByName = (acc, editorBlockObj, name) => {
		editorBlockObj.forEach(el => {
			if(el.name == name){
				let exists = acc.find(o => o.clientId === el.clientId);
				if(!exists){
					acc.push(el);
				}
			}else if(el.innerBlocks.length){
				acc = $.fn.frGuten.getBlocksByName(acc, el.innerBlocks, name);
			}
		});
		return acc;
	}

	$.fn.frGuten.updateBlockAttributes = (clientId, data) => {
		wp.data.dispatch('core/block-editor').updateBlockAttributes(clientId, data);
	}

	$.fn.frGuten.getSelectedBlock = () => {
		return wp.data.select('core/block-editor').getSelectedBlock();
	}

	$.fn.frGuten.getBlocksByClientId = (clientId) => {
		let result = [];
		result = wp.data.select('core/block-editor').getBlocksByClientId(clientId);

		return result.filter(el => {
			return el != null;
		});
	}
 
}(jQuery));