(function ($) {

    /**
     * This function is use to convert object to query string
     *
     * @param {object} obj - Object with params
     */
    function objectToQueryString(obj) {
        // If requested obj is not typeof object
        if (typeof (obj) !== 'object') {
            return obj;
        }

        let queryStr = '';
        // Object.keys(obj).forEach((key) => {
        //     // If array
        //     if(Array.isArray(obj[key])){
        //         obj[key].forEach(function (element) {
        //             queryStr += encodeURIComponent(key) + '[]=' + encodeURIComponent(element) + '&';
        //         });
        //     }
        //     else {
        //         queryStr += encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]) + '&';
        //     }
           
        // });

        queryStr = Object.keys(obj).map(key => key + '=' + obj[key]).join('&')

        return queryStr;
    }


    /**
     * This function is use call script same like ajax
     *
     * @param {object} $params - All params needed to ajax
     */
    $.frAjax = function ($params = {}) {

        // Set default params
        $params.data = $params.data || {};

        // Set url
        $params.url = $params.url || window.themeData.ajaxUrl;
        $params.url += '?';

        // If action mention separately
        if ($params.action) {
            // Add action in url
            $params.url += 'action=' + $params.action + '&';

            if (typeof ($params.data) === 'object' && !$params.data.action) {
                $params.data.action = $params.action;
            }
            else if(typeof ($params.data) !== 'object' && !$params.data.includes('action=')){
                $params.data += '&action=' + $params.action;
            }
        }

        // Prepare params for fetch data
        let $fetch_params = {
            method: $params.method || 'get',
            credentials: 'same-origin',
            headers: {
                'content-type': 'application/x-www-form-urlencoded',
                'accept': 'application/' + ($params.dataType || 'json'),
            },
        };

        // Check method type
        if($fetch_params.method.toLowerCase() === 'post'){
            $fetch_params.body = objectToQueryString($params.data);
        }
        else {
            $params.url += objectToQueryString($params.data);
        }

        let returnPromise = new Promise((resolve, reject) => {
            // Call fetch 
            fetch(
                $params.url,
                $fetch_params)
                .then(res => {
                    // If not getting response
                    if (!res.ok) {
                        reject(new Error(`HTTP Error ${res.status} ${res.statusText}`));
                        return;
                    }

                    return res.json(); // parse json body (if you got one)
                })
                .then(resData => {
                    // If success false
                    if (!resData.success) {
                        reject(new Error(`WP Error ${resData.data.error || 'Data error'}`));
                        return;
                    }

                    resolve(resData.data);
                    if($params.onComplete) $params.onComplete();
                    return;
                })
                .catch((error) => {
                    reject(new Error(`HTTP Error ${error}`));
                    if($params.onComplete) $params.onComplete();
                    return;
                });
        });

        return returnPromise;
    }


    /**
     * This function is use to scroll element to requested position
     *
     * @param {object} toElement - Html element need to scroll
     * @param {number} to - Position to scroll
     * @param {number} duration - Animation duration to scroll
     */
    $.frScrollTo = function (toElement, additionalPixel) {
        // Calculate final position
        let windowHeight = $(window).height();
        let top = $('html').scrollTop();
        let bottom = top + windowHeight;
        let finalPos = (toElement === null ?  top : toElement.offset().top) + additionalPixel;
      
        // Check if requested element already in scroll
        if (finalPos > (top) && finalPos < bottom) {
            return false;
        }

        scroll({
            top: finalPos,
            behavior: 'smooth',
        });

        return true;
    },

    // Function for open modal popup
    $.frModalOpen = function ($modal) {
        $($modal).trigger('fr:showModal');
    }

    // Function for close modal popup
    $.frModalClose = function ($modal) {
        $($modal).trigger('fr:hideModal');
    }

    // Function title to field name
    $.frConvertFormFieldName = function (fieldTitle) {
        let str = fieldTitle.toLowerCase().split(' ');
        for (var i = 0; i < str.length; i++) {
            str[i] = str[i].toLowerCase(); 
        }
        return str.join('_');
    }

    // Function field name to title
    $.frConvertFormFieldTitle = function (fieldName) {
        let str = fieldName.toLowerCase().split('_');
        for (var i = 0; i < str.length; i++) {
            str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1); 
        }
        return str.join(' ');
    }

})($);

