(function($) {
    const loadEllipsisLibrary = () => {
        window.fetchInject([
            'https://cdn.jsdelivr.net/npm/jquery-ellipsis@0.1.6/dist/jquery.ellipsis.min.js',
        ]).then(() => {
            console.log('jQuery Ellipsis loaded');
            $(window).trigger('fr:ellipsis-loaded');
        });
    }

    $(() => {
        const $ellipsisContainers = $('.requires-ellipsis');
        if($ellipsisContainers.length){
            loadEllipsisLibrary();
        }
        $(window).on('fr:load-ellipsis-library', () => {
            loadEllipsisLibrary();
        });
    });
})($);