
    /********
     * FrUtils
     */
    const FrUtils = function () {
        var _ = this;


        // Set url params
        _.urlParams = {};
        _.urlHashValue = null;
        _.urlBase = window.location.protocol + '//' + window.location.host;

        // utils init
        _.init = () => {
            _.urlParams = _.getUrlParams();
        }

        // Get url params
        _.getUrlParams = (url = window.location.href) => {
            var vars = {},
                hash;
            // Remove hash from url
            _.urlHashValue = (window.location.hash ? window.location.hash.substr(1) : null);

            if(_.urlHashValue){
                url = url.substr(0, url.indexOf('#'));
            }

            var hashes = (url.includes('?') ? url.slice(url.indexOf('?') + 1).split('&') : []);
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        // Set url params
        _.setUrlParams = (params) => {
            _.urlParams = params;
            _.refreshHistoryUrlParam();
        }

        // Set url hash
        _.setUrlHash = (hashValue) => {
            _.urlHashValue = (hashValue && hashValue !== '') ? hashValue : null;
            _.refreshHistoryUrlParam();
        }

        // Remove url hash
        _.removeUrlHash = () => {
            _.urlHashValue = null;
            _.refreshHistoryUrlParam();
        }

        // Check url has given params
        _.hasUrlParam = (param) => {
            return _.urlParams.hasOwnProperty(param)
        }

        // Add history url param
        _.addHistoryUrlParam = (key, value) => {
            _.urlParams[key] = value;
            _.refreshHistoryUrlParam();
        }

        // Remove history url param
        _.removeHistoryUrlParam = (key) => {
            delete _.urlParams[key];
            _.refreshHistoryUrlParam();
        }

        // Refresh history url param
        _.refreshHistoryUrlParam = () => {
            if (history.pushState) {
                var newurl = _.urlBase + window.location.pathname;

                // Having any url params
                if(Object.keys(_.urlParams).length > 0){
                    newurl += '?' + Object.keys(_.urlParams).map(key => key + '=' + _.urlParams[key]).join('&');
                }

                // Add hash if any
                newurl += ( _.urlHashValue ? ('#' + _.urlHashValue) : '');
                
                window.history.pushState({
                    path: newurl,
                }, '', newurl);
            }
        }


        // Change history url
        _.changeHistoryUrl = (url) => {
            if (history.pushState) {
                var tmpAnchorTag = $('<a>', { href:url } )[0];
                history.pushState(null, null, _.urlBase + tmpAnchorTag.pathname);
            }
        }
    }

    /******************
     * Initialise utils
     */
    var frUtils = new FrUtils();
    frUtils.init();

    export default frUtils; 

    