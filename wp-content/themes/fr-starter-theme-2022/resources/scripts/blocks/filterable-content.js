import frUtils from '../util/fr-util';
import frSticky from '../components/fr-sticky';

(function ($) {
	$.fn.filterableContent = function () {

        return this.each((i, element) => {

            const $self = $(element);
            let ajaxConfig = JSON.parse(($self.attr('ajax-config') ? $self.attr('ajax-config') : '{}'));
            let $ajaxContainer = $self.find('.ajax-container');
            let $form = $self.find('.filters-form');
            let $searchForm = $self.find('.search-bar-container form');
            let $headerForm = $self.find('.header-form');
            let $filters = $self.find('input[type="checkbox"], input[type="radio"]');
            let $clearBtn = $self.find('.clear-filters-btn');
            let $resultContainer = $self.find('.filters-result-container');
            let $searchInput = $searchForm.find('input');
            const $cardsContainer = $self.find('#cards-container');
            const $loadMoreBtn = $self.find('.load-btn-container a');
            let ajaxRunning = false;
            let hasMore = true;
            let loaded = false;
            let filters = [
                's',
                'event_time_type',
                'program_tax',
                'role_tax',
                'format_tax',
                'event-type_tax',
                'news-type_tax',
                'resource-type_tax'];

            let taxonomyFilters = [
                'event_time_type',
                'program_tax',
                'role_tax',
                'format_tax',
                'event-type_tax',
                'news-type_tax',
                'resource-type_tax'];

            let canChangeUrl = true;

            // On filters change
            $filters.on('change', (e) => {
                const _self = e.target;
                $($self).find(`input[type=checkbox][data-filter_name=${$(_self).attr('data-filter_name')}][value=${$(_self).val()}]`).prop('checked', $(_self)[0].checked);

                // Fix for radio
                if($(_self).attr('name') === 'event_time_type[]'){
                    $($self).find(`input[type=radio][data-filter_name=event_time_type]`).prop('checked', false);
                    $(_self).prop('checked', true);
                }

                updateFilters(_self);
            });

            // On click clear button
            $clearBtn.on('click', () => {
                clearFilters();
            });

            $searchForm.on('submit', (e) => {
                e.preventDefault();
                updateFilters();
            });

            // Function for initialize
            const init = () => {
                frUtils.init();
                setFiltersOnLoading();
            }

            // Function for update filters
            const updateFilters = ($clickedFilter) => {
                let needReset = true;
                let childFilters;
                ajaxConfig.page = 1;

                // Select all childs
                if ($clickedFilter) {
                    childFilters = $($clickedFilter).closest('.accordion-item').find('input[type="checkbox"]');

                    childFilters.each((i, childFilter) => {
                        $(childFilter).prop('checked', $($clickedFilter)[0].checked);
                    });
                }

                // Clear all array
                $filters.each((index, $filter) => {
                    ajaxConfig[$($filter).attr('data-filter_name')] = [];
                });

                // Add params
                $filters.each((index, $filter) => {
                    if ($($filter)[0].checked && !ajaxConfig[$($filter).attr('data-filter_name')].includes($($filter).val())) {
                        ajaxConfig[$($filter).attr('data-filter_name')].push($($filter).val());
                        needReset = false;
                    }
                });

                // Add search text
                if ($searchInput[0]) {
                    ajaxConfig[$($searchInput).attr('name')] = $($searchInput).val();
                    if ($($searchInput).val() !== '') needReset = false;
                }

                // If need reset
                if (needReset) resetFilters();

                setResultByFilters();
            }

            // Function for clear filters
            const clearFilters = () => {
                $filters.prop('checked', false);
                $form[0]?.reset();
                $searchForm[0]?.reset();
                $headerForm[0]?.reset();

                // If search text
                if ($searchInput[0]) {
                    $($searchInput).val('');
                }

                // Select upcoming events on clear
                if($headerForm[0]){
                    $($self).find(`input[type=radio][data-filter_name=event_time_type][value=upcoming]`).prop('checked', true);
                }

                updateFilters();
            }

            // Function for reset filters
            const resetFilters = () => {
                // ajaxConfig = JSON.parse(($filterContainer.attr('ajax-config')?$filterContainer.attr('ajax-config'):'{}'));
            }

            // On click load more button
            $loadMoreBtn.on('click', () => {
                loadNextPage();
            });

            // Function for set pagination
            const setPagination = (resData) => {
                hasMore = resData.hasMore;
                // If hasMore
                if (hasMore) {
                    $resultContainer.addClass('has-more-pages');
                }
                else {
                    $resultContainer.removeClass('has-more-pages');
                }
                
                // Add fr status
                if(resData.cards.length === 0 && ajaxConfig.page === 1){
                    $self.attr('fr-status', 'no-results-found');
                }
                else if(!hasMore) {
                    $self.attr('fr-status', 'no-more-results');
                }
                else {
                    $self.attr('fr-status', '');
                }
            }
            
            
            // On window url change
            window.onpopstate = function (event) {
                if (event.state) {
                    setFiltersOnLoading();
                } else {
                    // history changed because of a page load
                }
            }


            // Function to change url
            const changeWindowUrl = (ajaxConfig) => {
                // Don't do anything
                if (!canChangeUrl) return;

                var urlParams = {};
                filters.forEach((filterName) => {
                    if (ajaxConfig[filterName] && ajaxConfig[filterName].length > 0) {
                        urlParams[filterName] = ajaxConfig[filterName];
                    }
                });

                frUtils.setUrlParams(urlParams);
            }

            // Function to set filters value on loading
            const setFiltersOnLoading = () => {
                if(!ajaxConfig.show_filters_in_frontend){
                    setResultByFilters();
                    return;
                }
                
                let urlParams = frUtils.getUrlParams();

                // Reset all checkboxes
                $form[0]?.reset();
                $headerForm[0]?.reset();
                $searchForm[0]?.reset();

                // Select upcoming events on load
                if($headerForm[0]){
                    $($self).find(`input[type=radio][data-filter_name=event_time_type][value=upcoming]`).prop('checked', true);
                }

                // Set all filters
                filters.forEach((filterName) => {
                    // Return if not having urlParam for current filter
                    if (!urlParams.hasOwnProperty(filterName)) return;

                    // Check if current filterName is search
                    if (filterName === 's') {
                        $($searchInput).val(urlParams[filterName]);
                    }

                    // Check if current filterName is event_time_type
                    // if (filterName === 'event_time_type') {
                    //     $($searchInput).val(urlParams[filterName]);
                    // }

                    // Check if current filterName is taxonomy filter
                    if (taxonomyFilters.includes(filterName)) {
                        let presentValues = urlParams[filterName].split(',');
                        presentValues.forEach((value) => {
                            $($self).find(`input[type=checkbox][data-filter_name=${filterName}][value=${value}]`).prop('checked', true);
                            $($self).find(`input[type=radio][data-filter_name=${filterName}][value=${value}]`).prop('checked', true);
                        });
                    }
                });

                ajaxConfig.page = 1;
                canChangeUrl = !!$headerForm[0];
                updateFilters();
            }

            // Function for get html data by selected filters
            const setResultByFilters = () => {
                $self.attr('fr-status', 'loading-result');
                if(loaded){
                    $.frScrollTo($($self), -50);
                }else{
                    loaded = true;
                }
                // Get data using ajax
                $.frAjax({
                    action: ajaxConfig.action,
                    data: ajaxConfig,
                    onComplete: () => {
                        $ajaxContainer.removeClass('loading');
                        changeWindowUrl(ajaxConfig);
                        canChangeUrl = true;
                        frSticky.update();
                    },
                }).then(resData => {
                    $cardsContainer.html('');
                    $.each(resData.cards, (i, card) => { 
                        $cardsContainer.append(card);
                    });

                    setPagination(resData);
                }).catch(err => {
                    $self.attr('fr-status', '');
                    console.log(`Error ${err}`);
                });
            }

            // Function for get html data for next page
            const loadNextPage = () => {
                if (ajaxRunning) return;
                if (!hasMore) return;
                
                //Start
                ajaxRunning = true;
                $self.attr('fr-status', 'ajax-running');

                ajaxConfig.page += 1;

                // Get data using ajax
                $.frAjax({
                    action: ajaxConfig.action,
                    data: ajaxConfig,
                    onComplete: () => {
                        ajaxRunning = false;
                        frSticky.update();
                    },
                }).then(resData => {
                    $.each(resData.cards, (i, card) => { 
                        $cardsContainer.append(card);
                    });
                    setPagination(resData);
                }).catch(err => {
                    $self.attr('fr-status', '');
                    console.log(`Error ${err}`);
                });
            }
            

            init();
        });
	}

	$(() => {
		$('.filters-container').filterableContent();
	});
})($);