(function($) {
	const doAjax = ($self, config, callback) => {
		const { ajaxConfig, filtersParamStr, shouldEmptyContainer, $gridContainer } = config;

		$self.ajaxRunning = true;
		$self.noMoreResults = false;

		//setup before
		$self.attr('fr-status', 'ajax-running');

		if (shouldEmptyContainer) {
			$gridContainer.find('.fr-card').remove();
		}

		$.ajax({
			url: ajaxConfig.url + '?action='+ajaxConfig.action + '&page_number=' + ajaxConfig.page_number + '&posts_per_page=' + ajaxConfig.posts_per_page + '&search=' + ajaxConfig.search + '&' + filtersParamStr,
			method: 'GET',
		}).done((resp) => {
			$self.attr('fr-status', '');
			if (resp.success) {
				populateCardsContainer($gridContainer, resp.data);

				if(resp.data.length == 0){
					if(ajaxConfig.page_number == 0){
						$self.attr('fr-status', 'no-results-found');
					}else{
						if (resp.data.length < ajaxConfig.posts_per_page) {
							$self.attr('fr-status', 'no-more-results');
							$self.noMoreResults = true;
						}
					}

				}else{
					if (resp.data.length < ajaxConfig.posts_per_page) {
						$self.attr('fr-status', 'no-more-results');
					}
				}
			}
		}).fail(() => {
			alert('There was an error processing your request, please try again later.');
			$self.attr('fr-status', '');
		}).always(() => {
			$self.ajaxRunning = false;
			$(window).trigger('fr:trigger-ellipsis');
			if (callback) callback();
		});
	};
	
	const populateCardsContainer = ($container, data) => {
		$.each(data, (i, el) => { 
			$container.append($(el));
		});
	};

	const highlightSearchTermsInCards = ($gridContainer, searchedText) => {
		$.each($gridContainer.find('.fr-card:not(.is--highlighted-text)'), (i, el) => {
			const $card = $(el);
			let title = $card.find('h3')[0].innerHTML;
			let description = $card.find('p')[0].innerHTML;
			let re = new RegExp(searchedText,"g"); // search for all instances
			let newTitle = title.replace(re, `<mark>${searchedText}</mark>`);
			let newDescription = description.replace(re, `<mark>${searchedText}</mark>`);

			$card.find('h3')[0].innerHTML = newTitle;
			$card.find('p')[0].innerHTML = newDescription;

			$card.addClass('is--highlighted-text');
		});
	}
	
	$.fn.searchResultsContainer = function() {
		return this.each(function() {
			const $self = $(this);
			$self.ajaxRunning = false; 
			$self.filtersAreResetting = false;
			$self.noMoreResults = false;
			const ajaxConfig = JSON.parse($self.attr('ajax-config') || '{}');
			const $gridContainer = $self.find('.card-grid-container');
			const filterGroupId = ajaxConfig.filter_group_id ? ajaxConfig.filter_group_id : '';
			const $filtersGroup = $(`[fr-filter-group="${filterGroupId}"]`);
			const $loadMoreBtn = $self.find('.load-btn-container > .cta-button');
			const $clearAllFiltersBtn = $self.find('[fr-clear-all-filters]');

			$.each($filtersGroup.find('select'), (i, el) =>  {
				$(el).on('change', () => {
					if ($self.ajaxRunning) return;

					ajaxConfig.page_number = 0;

					const filtersParamStr = $.fn.frGetFiltersFormUrlParams($filtersGroup);
					
					//trigger url update
					$(window).trigger('fr:update-url-params', [filtersParamStr]);
					
					if($self.filtersAreResetting) return;

					doAjax($self, {
						ajaxConfig: ajaxConfig,
						$gridContainer: $gridContainer,
						filtersParamStr: filtersParamStr,
						shouldEmptyContainer: true,
					}, () => {
						ajaxConfig.page_number++;
						highlightSearchTermsInCards($self, ajaxConfig.search);
					});
				})
			});
			
			$loadMoreBtn.on('click', () => {
				doAjax($self, {
					ajaxConfig: ajaxConfig,
					$gridContainer: $gridContainer,
					filtersParamStr: $.fn.frGetFiltersFormUrlParams($filtersGroup),
					shouldEmptyContainer: false,
				}, () => {
					ajaxConfig.page_number++;
					highlightSearchTermsInCards($self, ajaxConfig.search);
				});
			});

			$clearAllFiltersBtn.on('click', () => {
				$self.filtersAreResetting = true;

				$.each($filtersGroup.find('select'), (i, selectEl) => { 
					$(selectEl).prop('selectedIndex',0);
					$(selectEl).trigger('change');
				});

				$self.filtersAreResetting = false;

				$loadMoreBtn.trigger('click');
			});
			
            //on page load
            $loadMoreBtn.trigger('click');
		});
	};
	
	$(() => {
		$('.search-results-container').searchResultsContainer();
	});
})(jQuery);
