@if ((isset($phone) && $phone) || (isset($email) && $email))
    <div class="phone-email-button no-arrow-buttons" @if ($component_id)
        fr-component-id="{{ $component_id }}"
        @if ($enable_recaptcha_validation)
            ajax-config="{{ \App\Providers\PersonRecaptchaProvider::GetAjaxConfig() }}"
        @endif
    @endif>
        @if ($email)
            <x-cta-button :label="$email_button_label" type="external_url" :external-url="$email" style="primary" icon="email"/>
        @endif
        @if ($phone)
            <x-cta-button :label="$enable_recaptcha_validation ? '--- --- ----' : $phone_button_label" type="external_url" :external-url="$enable_recaptcha_validation ? 'javascript:void(0)' : $phone" style="primary" icon="phone"/>

            @if ($enable_recaptcha_validation)
                <script type="text/javascript">
                    var componentId = '{{ $component_id }}';
                    var postId = '{{ $post_id }}';
                    var recaptchaCallback__{{ $component_id }} = function(token) {
                        $(window).trigger('fr:verify-recaptcha-person', [componentId, token, postId]);
                    };
                    var ticker = setInterval(function(){
                        try{
                            grecaptcha.execute();
                            clearInterval(ticker);
                        } catch(e) {}
                    }, 20);
                </script>
                <div class='g-recaptcha' data-sitekey='{{ isset($keys['site_key']) ? $keys['site_key'] : '' }}' data-callback='recaptchaCallback__{{ $component_id }}' data-size='invisible'></div>
                <script src="//www.google.com/recaptcha/api.js" async defer></script>
            @endif
        @endif
    </div>
@endif