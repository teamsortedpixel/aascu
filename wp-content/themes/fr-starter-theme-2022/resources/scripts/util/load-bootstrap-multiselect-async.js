(function($) {
    $(() => {
        const $bsMultiselectContainer = $('.bootstrap-multiselect, [multiselect-config]');
        if($bsMultiselectContainer.length){
            window.fetchInject([
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js',
            ]).then(() => {
                console.log('Bootstrap Multiselect.js loaded');
                $(window).trigger('fr:bootstrap-multiselect-js-loaded');
            });    
        }
    });
})($);
