(function ($) {
	$.fn.networkMap = function () {

		const checkIfIosMobile = () => {
			return navigator && (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i));
		} 

		const initializeTooltip = ($tooltip, moduleId) => {
			if(!$tooltip.length) return;
			$tooltip.attr('fr-tooltip-for', moduleId).appendTo('body');
		}

		const initializePanZoom = ($zoomableLayer, $buttonsContainer) => {
			const instance = Panzoom($zoomableLayer[0], {
				contain: 'outside',
				startScale: 1,
				animate:true
			});
			$buttonsContainer.find('[data-zoom-in]')[0].addEventListener('click', instance.zoomIn)
			$buttonsContainer.find('[data-zoom-out]')[0].addEventListener('click', instance.zoomOut)

			return instance;
		}

		const populateTooltipData = ($tooltip, data) => {
			let list = '';
			$tooltip.find('.title-container h4').html(data.state_name);

			data.list.forEach(el => {
				list += `<li>
					<p>
						<b>`+(el.link? `<a href="${el.link}" target='_blank'>${el.name}</a>` : `${el.name}`)+`</b>
						<span class="city">${el.city}</span>
					</p>
				</li>`;
			});

			$tooltip.find('.list-container ul').html(list);
		}

		const toggleShowTooltip = ($tooltip, show = false, $numberContainer = false, data = [], isMobileBp = false, $mobileTooltipContainer = false) => {
			if(show){
				if(isMobileBp){
					if($mobileTooltipContainer){
						$mobileTooltipContainer.html('');
						$tooltip.appendTo($mobileTooltipContainer);
					}
				}else{
					if($numberContainer && $numberContainer.length){
						$tooltip.appendTo($numberContainer.find('.tooltip-container'));
					}
				}

				if(data){
					populateTooltipData($tooltip, data);
				}

				$tooltip.addClass('show');
			}else{
				$tooltip.removeClass('show');
			}
		}

		const initializeTooltipScrollbar = ($tooltip) => {
			new SimpleBar($tooltip.find('.list-container')[0], { autoHide: false });
		}

		const addTooltipClassForSpecificStates = (stateAbv) => {
			let result = '';
			const states = [
				'NM',
				'AZ',
				'TX',
				'LA',
				'MS',
				'AL',
				'GA',
				'FL',
				'AR',
				'OK'
			];

			if(states.includes(stateAbv)){
				result = 'top-right-position';
			}

			return result;
		}

		const addHoverableCircles = ($map, $mapContainer, data = []) => {
			const mapWidth = parseFloat($map.attr('width'));
			const mapHeight = parseFloat($map.attr('height'));

			$.each($map.find('rect[state-abv]'), (i, el) => { 
				const $el = $(el);
				const rectWidthPerc = (($el.width() * 100) / mapWidth);
				const rectHeightPerc = (($el.height() * 100) / mapHeight);
				const $number = $('<div class="number-container" state-abv="'+$el.attr('state-abv')+'"><div class="number-container-inner"><span></span><div class="tooltip-container '+addTooltipClassForSpecificStates($el.attr('state-abv'))+'"></div></div></div>');
				const position = $el.position();

				if(position.left !== 0 && position.top !== 0){
					const coordinates = $el.attr('transform').replace('matrix(', '').replace(')', '').split(' ');

					$number.css({
						left: ((parseFloat(coordinates[4]) * 100) / mapWidth) - rectWidthPerc  + '%',
						top: ((parseFloat(coordinates[5]) * 100) / mapHeight)  + '%',
						width: rectWidthPerc + '%',
						height: rectHeightPerc + '%',
					}).appendTo($mapContainer);
				}
			});
		}

		const calculateHorizontalOffset = ($tooltip, $numberContainer) => {
			const tooltipWidth = parseFloat($tooltip.find('.network-map-tooltip-inner').css('width'));

			if($numberContainer.offset().left + tooltipWidth > $(window).width()){
				$tooltip.css('transform', 'translateX(-'+($numberContainer.offset().left + tooltipWidth + 10 - $(window).width())+'px)');
			}else{
				$tooltip.css('transform', '');
			}
		}

		return this.each((i, el) => {
			const $self = $(el);
			const moduleId = $self.attr('fr-module-id');
			const $mapLayer = $self.find('.network-map-layer');
			const $map = $mapLayer.find('svg');
			const data = JSON.parse($self.attr('map-module-data') || '[]');
			const $tooltip = $self.find('.network-map-tooltip');
			const $mobileTooltipContainer = $self.find('.tooltip-container-mobile');
			const $mobileZoomButtons = $self.find('.mobile-zoom-buttons');
			const isiOS = checkIfIosMobile();
			
			//vars
			$self.currentBreakpoint = false;
			$self.zoomPanLibraryLoaded = false;
			$self.zoomPanInstance = false;

			addHoverableCircles($map, $mapLayer.find('.map-aspect-ratio-container'), data);

			initializeTooltip($tooltip, moduleId);

			$(window).on('fr:simplescrollbarjs-loaded', () => {
				initializeTooltipScrollbar($tooltip);
			});

			//add hover effects to map items
			$mapLayer.find('path[state-abv], div[state-abv], rect[state-abv]').hover((ev) => {
				const $el = $(ev.currentTarget);
				const stateAbv = $el.attr("state-abv");
				const $numberContainer = $mapLayer.find(`div[state-abv="${$el.attr("state-abv")}"]`);
				
				if(!['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
					calculateHorizontalOffset($tooltip, $numberContainer);
				}

				//add active class
				$mapLayer.find(`[state-abv="${stateAbv}"]`).addClass('highlighted');

				//show tooltip
				toggleShowTooltip($tooltip, true, $numberContainer, data[stateAbv], ['xs', 'sm', 'md'].includes($self.currentBreakpoint), $mobileTooltipContainer);

			}, (ev) => {
				const $el = $(ev.currentTarget);
				const stateAbv = $el.attr("state-abv");
				$mapLayer.find(`path[state-abv="${stateAbv}"], div[state-abv], rect[state-abv="${stateAbv}"]`).removeClass('highlighted');

				if(!['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
					toggleShowTooltip($tooltip, false);
				}
			});

			//since hover doesn't work on tap in iOS
			if(isiOS){
				$mapLayer.find('path[state-abv], div[state-abv], rect[state-abv]').on('click', (ev) => {
					if(['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
						const $el = $(ev.currentTarget);
						const stateAbv = $el.attr("state-abv");
						const $numberContainer = $mapLayer.find(`div[state-abv="${$el.attr("state-abv")}"]`);
		
						//add active class
						$mapLayer.find(`[state-abv="${stateAbv}"]`).addClass('highlighted');

						//remove active class from others
						$mapLayer.find(`[state-abv]:not([state-abv="${stateAbv}"])`).removeClass('highlighted');
		
						//show tooltip
						toggleShowTooltip($tooltip, true, $numberContainer, data[stateAbv], true, $mobileTooltipContainer);
					}
				})
			}

			$(window).on('resize fr:pseudo-resize', () => {
                const newBreakpoint = window.currentBreakpoint();

                if(newBreakpoint !== $self.currentBreakpoint){
                    //re-do stuff
                    if(['xs', 'sm', 'md'].includes(newBreakpoint)){
						//moble

						//reset horizontal offset of tooltip
						$tooltip.css('transform', '');

						if(!$self.zoomPanInstance){
							if(!$self.zoomPanLibraryLoaded){
								$(window).on('fr:panzoomjs-loaded', () => {
									$self.zoomPanLibraryLoaded = true;
									$self.zoomPanInstance = initializePanZoom($mapLayer.find('.network-map-layer-inner'), $mobileZoomButtons);
								});
								$(window).trigger('fr:load-panzoomjs');
							}else{
								$self.zoomPanInstance = initializePanZoom($mapLayer.find('.network-map-layer-inner'), $mobileZoomButtons);
							}
						}
                    }else{
						if($self.zoomPanInstance){
							$self.zoomPanInstance.destroy();
							$self.zoomPanInstance = false;

							//remove some styles that the lbirary adds
							$mapLayer.find('.network-map-layer-inner')[0].style = '';
							$mapLayer[0].style = '';
						}
                    }

                    $self.currentBreakpoint = newBreakpoint;
                }
            });

			//on page load
			$(window).trigger('fr:pseudo-resize');
		});
	}

	$(() => {
		$('.network-map-module').networkMap();
	});
})(jQuery);