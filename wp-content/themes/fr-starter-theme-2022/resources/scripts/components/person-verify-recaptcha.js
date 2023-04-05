(($) => {
	$(window).on('load', () => {
        console.log('hello');
        $(window).on('fr:verify-recaptcha-person', (ev, componentId, token, post_id) => {
            const $element = $(`.phone-email-button[fr-component-id="${componentId}"]`);
            
            if($element.length === 0) return;
            
            const ajaxConfig = JSON.parse($element.attr('ajax-config') || '{}');

            $.ajax({
                url: ajaxConfig.url,
                data: {...ajaxConfig, ...{
                    token: token,
                    post_id: post_id
                }},
                method: 'POST',
            }).done((resp) => {
                if(resp.success){
                    const $phoneBtn = $element.find('a:contains("--- --- ----")');
                    if($phoneBtn.length){
                        $phoneBtn.attr('href', resp.data.phone_uri);
                        $phoneBtn.html($phoneBtn.html().replace("--- --- ----", resp.data.phone_label));
                    }
                }else{
                    console.log('resp', resp);
                }
            }).fail(() => {
                console.log('There was an error processing your request, please try again later.');
            });

        })
	});
})($);