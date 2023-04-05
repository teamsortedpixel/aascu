(function ($) {

  $.fn.splideSlider = function () {

    return this.each((i, el) => {
      const $self = $(el);
      const $slider = $self.find('.splide');
      const slideCount = $slider.find('.splide__slide').length;

      new Splide($slider[0], {
        type   : 'loop',
        arrows: slideCount > 1,
        drag: slideCount > 1,
        pagination: false
      }).mount();

    });
  };

  $(() => {
    $(window).on("fr:splidejs-loaded", () => {
      $(".module-testimonials-slider").splideSlider();
    });
  });
})($);

