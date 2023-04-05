
/********
 * FrSticky
 */
const FrSticky = function () {
    let _ = this;
    let $stickySidebar;
    let $navBar = $('body > header');
    let $mainSidebar = $('.main-sidebar');

    let menuLink = $('.main-left-content li a');
    let activeLinks = [];
    let firstLink = menuLink.eq(0);
    // let menuProcess = false;

    // sticky init
    _.init = () => {


        if ($('.main-content-wrapper').length === 0) {
            return;
        }

        $stickySidebar = new window.StickySidebar('.main-left-content', {
            containerSelector: '.main-content-wrapper',
            innerWrapperSelector: '.main-sidebar',
            topSpacing: 100,
        });

    }

    // sticky update
    _.update = () => {
        if(!$stickySidebar){
            return;
        }

        $stickySidebar.updateSticky();
    }


    // sticky destroy
    _.destroy = () => {
        if(!$stickySidebar){
            return;
        }
        
        $stickySidebar.destroy();
    }

    // Set active link to sidebar
    _.setSidebarActiveLink = () => {
        
        // if(menuProcess){
        //     return;
        // }

        // menuProcess = true;

        let windowpos = window.scrollY || window.scrollTop;
        activeLinks = [];

        $('.anchor-module-container').each(function () {
            let nextAnchor = $(this).nextAll('.anchor-module-container');
            let currentAnchorBottom = nextAnchor[0] ? $(nextAnchor[0]).offset().top : 0;

            if (($(this).offset().top) > windowpos && (currentAnchorBottom > windowpos || currentAnchorBottom === 0)) {
                let corrospondingLink = $('.main-left-content').find('a[data-go-to-anchor="#'+$(this).attr('id')+'"');
                activeLinks.push(corrospondingLink);
            }
        });

        // Add class active to first link from all active sections
        if (activeLinks.length > 0 && !activeLinks[0].hasClass('.active')) {
            menuLink.removeClass('active');
            activeLinks[0].addClass('active');
        }

        // Add class active to first link if there is no any active section
        if (activeLinks.length === 0 && !firstLink.hasClass('active')) {
            menuLink.removeClass('active');
            firstLink.addClass('active');
        }

        //setTimeout(function(){ menuProcess = false; }, 1000);


    }


    // Sidebar menu
    _.initializeSidebarMenu = () => {


        if ($('.main-content-wrapper').length === 0) {
            return;
        }

        _.setSidebarActiveLink();

        // On scroll change menu activeness
        $(window).on('scroll', function () {
            _.setSidebarActiveLink();
        });

        // On scroll change menu activeness
        $(window).on('scroll', function () {
            // When header sticky nav fixed 
            if ($navBar.hasClass('fixed') && $mainSidebar.css('position') === 'fixed') {
                $mainSidebar.css('margin-top', '80px');
            }
            else {
                $mainSidebar.css('margin-top', '0px');
            }
            
            if($mainSidebar.css('position') === 'fixed'){
                $mainSidebar.css('top', $navBar.outerHeight() + 20);
            }
        });
    }

}

/******************
 * Initialise sticky
 */
var frSticky = new FrSticky();

export default frSticky;



