(function ($) {

  $.fn.upcomingOpportunities = function () {
    return this.each((i, el) => {
      const $self = $(el);
      let $sideOpen = $self.find('.upcoming-opportunities-side-label');
      let $sideClose = $self.find('.side-label-hover');  

      // On click side label
      $sideOpen.on('click', (e) => {
        e.stopPropagation();
        $($self).addClass('show');
      });

      // On click side label
      $sideClose.on('click', (e) => {
        e.stopPropagation();
        $($self).removeClass('show');
      });

      // On click side label
      $(document).on('click.upcoming-opportunities-container', (e) => {
        if(!$(e.target).closest('.upcoming-opportunities-slide-panel').length && $self.hasClass('show')){
          $($self).removeClass('show');
        }
      });
    });
  };

  $.fn.upcomingOpportunitiesSlider = function () {
    
    return this.each((i, el) => {
      const $self = $(el)[0];
      const slideCount = $($self).find('.splide__slide').length;

      if(slideCount <= 1){
        return;
      }
  
      new Splide($self, {
        type:'loop',
        perPage: 2,
        autoplay: false,
        interval: 1000,
        speed: 500,
        easing: 'linear',
        perMove: 1,
        arrows: true,
        drag: true,
        pagination: false,
        gap: '10px',
        padding: '20px',
        breakpoints: {
          480: {
            perPage: 1,
          },
        }
      }).mount();    

    });
  };

  $(() => {
    $(window).on("fr:splidejs-loaded", () => {
      $(".event-cards-slider").upcomingOpportunitiesSlider();
    });

    $(".upcoming-opportunities-container").upcomingOpportunities();
  });
})($);