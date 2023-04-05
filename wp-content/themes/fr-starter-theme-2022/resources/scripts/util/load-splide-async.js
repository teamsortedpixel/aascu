(function($) {
    $(() => {
        const $splideContainer = $('.requires-splidejs');
        if($splideContainer.length){
            window.fetchInject([
                'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js',
            ]).then(() => {
                console.log('Splide.js loaded');
                $(window).trigger('fr:splidejs-loaded');
            });    
        }
    });
})($);
