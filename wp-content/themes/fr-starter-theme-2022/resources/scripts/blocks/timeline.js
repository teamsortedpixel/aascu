import { Modal } from 'bootstrap';

(function ($) {
	$.fn.timeline = function () {
        const initializeAnimatedLine = ($animatedLine, $slides) => {
            $animatedLine.css({
                'top' : '',
                'max-height' : '',
                'left': '',
                'width': '',
            });
            $animatedLine.find('span').css('transform', '');

            let lineWidth = 0;
            $.each($slides, (i, el) =>  { 
                lineWidth += $(el).width();
            });

            $animatedLine.css('left', '').css('width', '');
            $animatedLine.css('left', ($slides.eq(0).width() / 2) + parseFloat($slides.eq(0).css('padding-left')) + 'px');
            $animatedLine.css('width', (lineWidth - ($slides.last().width() / 2) - parseFloat($animatedLine.css('left'))) +'px');
        }

        const animateLine = ($animatedLine, $slides, activeIndex) => {
            const linePosX = $animatedLine.offset().left;
            const activeSlidePosX = $slides.eq(activeIndex).offset().left + ($slides.eq(activeIndex).width() / 2);
            
            $animatedLine.find('span').css('width', (activeSlidePosX - linePosX) + 'px');
        }

        const activeDots = ($slides, activeIndex) => {
            $.each($slides, (i, el) => { 
                $(el).toggleClass('active-dot', i <= activeIndex);
            });
        }

        const populateModalContent = ($modalContent, $modal, slidesData) => {
            $modal.find('.modal-body')
                .html('')
                .append($modalContent);

            $modal.find('.button--left > button').attr('disabled', slidesData.currentSlideIndex == slidesData.initialSlideIndex).attr('fr-modal-index', slidesData.prevIndex);

            $modal.find('.button--right > button').attr('disabled', slidesData.currentSlideIndex == slidesData.lastSlideIndex).attr('fr-modal-index', slidesData.nextIndex);
        }

        const initializeSlider = ($self, $instance, $slider) => {
            $instance = new Splide($slider[0], {
                perPage: 3,
                perMove: 1,
                autoWidth: true,
                focus: 'center',
                pagination: false
            });

            $instance.on('move', (newIndex, prevIndex, destIndex) => {
                $self.trigger('fr:on-slider-move', [newIndex, prevIndex, destIndex]);
            });

            $instance.mount(); 
        }

        const initializeMobileAnimatedLine = function($animatedLine, $slides, $slider) {
            $animatedLine.css({
                'top' : '',
                'max-height' : '',
                'left': '',
                'width' : ''
            });
            $animatedLine.find('span').css('width', '');
            
            const $firstRow = $slides.filter('.slide--first');
            const $lastRow = $slides.filter('.slide--last');
            const initialYPos = (parseFloat($firstRow.find('.timeline-card').outerHeight()) / 2);
            const finalYpost = (parseFloat($lastRow.find('.timeline-card').outerHeight()) / 2) + $lastRow.find('.spacer').outerHeight();
            const maxHeight = $slider.height() - initialYPos - finalYpost;
    
            $animatedLine.css({
                'top' : initialYPos + 'px',
                'max-height' : maxHeight + 'px'
            });
        }

        const getCurrentScroll = function(){
            if (window.pageYOffset != undefined) {
                return [window.pageXOffset, window.pageYOffset];
            } else {
                var sx, sy, d = document,
                    r = d.documentElement,
                    b = d.body;
                sx = r.scrollLeft || b.scrollLeft || 0;
                sy = r.scrollTop || b.scrollTop || 0;
                return [sx, sy];
            }
        }

        const animateMobileTimeLine = function($self, $animatedLine) {
            var screenHeight = $(window).height();
            var currPos = getCurrentScroll();
            var lineMaxHeight = parseFloat($animatedLine.css('max-height'));
            var centerPos = currPos[1] + (screenHeight / 2);
            var zPos = $self.offset().top;
            var heightVal = centerPos - zPos;
            var heightValAbs = ((((heightVal) * 100) / lineMaxHeight) / 100);
    
            heightValAbs = heightValAbs < 0 ? 0 : heightValAbs;
            heightValAbs = heightValAbs > 1 ? 1 : heightValAbs;
    
            $animatedLine.find('>div>span').css('transform', 'scaleY('+heightValAbs+')');
    
            animateMobileDots($self, $animatedLine, centerPos );
        }

        const animateMobileDots = function($self, $animatedLine, centerPos){
            $.each($self.find('.timeline-slide .timeline-line--dot'), function (i, el) {
                if(centerPos + ($animatedLine.offset().top - $self.offset().top) > $(el).offset().top + ($(el).width() / 2)){
                    $(el).closest('.timeline-slide').addClass('is--mobile-active-dot');
                }else{
                    $(el).closest('.timeline-slide').removeClass('is--mobile-active-dot');
                }
            });
        }

        const attachMobileScrollEvent = ($self, $animatedLine) => {
            if(!$self.hasClass('is--mobile-initialized')){
                $(window).on('scroll', function(ev){
                    if(['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
                        animateMobileTimeLine($self, $animatedLine);
                    }
                });
                $self.addClass('is--mobile-initialized');
            }
        }

		return this.each((i, el) => {
			const $self = $(el);
            const $slider = $self.find('.splide');
            const $animatedLine = $self.find('.animated-timeline-line');
            const $slides = $self.find('.timeline-slide');
            const $modal = $self.find('.timeline-modal');

            $self.sliderInstance = false;
            $self.currentBreakpoint = window.currentBreakpoint();

            $self.on('fr:on-slider-move', (ev, newIndex, prevIndex, destIndex) => {
                animateLine($animatedLine, $slides, newIndex);
                activeDots($slides, newIndex);
            });

            $self.on('fr:modal-open-slide', (ev, index) => {
                $modal.modal('hide');
                setTimeout(() => {
                    $slides.eq(parseInt(index)).find('[fr-open-modal]').trigger('click');
                }, 320);
            });

            $modal.find('.button--left > button').on('click', () => {
                $self.trigger('fr:modal-open-slide', [$modal.find('.button--left > button').attr('fr-modal-index')]);
            });

            $modal.find('.button--right > button').on('click', () => {
                $self.trigger('fr:modal-open-slide', [$modal.find('.button--right > button').attr('fr-modal-index')]);
            });

            //initialize modal
            new Modal($modal[0], {});

            $slides.find('[fr-open-modal]').on('click', (ev) => {
                const $el = $(ev.currentTarget);
                const currIndex = parseInt($el.attr('fr-index'));
                const lastIndex = parseInt($slides.last().find('.timeline-card').attr('fr-index'));
                const initIndex = parseInt($slides.first().find('.timeline-card').attr('fr-index'));

                const slideData = {
                    currentSlideIndex: currIndex,
                    initialSlideIndex: initIndex,
                    lastSlideIndex: lastIndex,
                    nextIndex: currIndex + 1,
                    prevIndex: currIndex - 1
                };

                //populate modal content
                populateModalContent($el.find('.modal-content').clone().html(), $modal, slideData);

                //show modal
                $modal.modal('show');
            });

            //DESKTOP
            if(!['xs', 'sm', 'md'].includes($self.currentBreakpoint)){
                //initialize slider
                initializeSlider($self, $self.sliderInstance, $slider);
    
                //initialize animated line
                initializeAnimatedLine($animatedLine, $slides);
            }else{
                initializeMobileAnimatedLine($animatedLine, $slides, $slider);

                attachMobileScrollEvent($self, $animatedLine);
            }

            //on window resize
            $(window).on('resize', () => {
                const newBreakpoint = window.currentBreakpoint();

                if(newBreakpoint !== $self.currentBreakpoint){
                    
                    //re-do stuff
                    if($self.sliderInstance){
                        console.log('destroy');
                        $self.sliderInstance.destroy();
                    }

                    if(['xs', 'sm', 'md'].includes(newBreakpoint)){
                        initializeMobileAnimatedLine($animatedLine, $slides, $slider);
                        attachMobileScrollEvent($self, $animatedLine);
                    }else{
                        initializeSlider($self, $self.sliderInstance, $slider);
                        initializeAnimatedLine($animatedLine, $slides);
                    }

                    $self.currentBreakpoint = newBreakpoint;
                }
            });
        });
	}

	$(() => {
        $(window).on('fr:splidejs-loaded', function(){
            $('.timeline-module').timeline();
		});
	});
})($);