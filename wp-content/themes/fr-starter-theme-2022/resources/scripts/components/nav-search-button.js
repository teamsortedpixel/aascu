(function ($) {
	$.fn.navSearchButton = function () {
        const toggleOpenDropdown = ($self, $btn, $input) => {
            $btn.toggleClass('is--opened', !$btn.hasClass('is--opened'));

            if(!$btn.hasClass('is--opened')){
                resetSearch($self);
            }else{
                $input.focus();
            }
        }

        const resetSearch = ($self) => {
            $self.find('input[type="text"]').val('');
        }

		return this.each((i, el) => {
			const $self = $(el);
            const $btn = $self.find('.search-btn.general-search-btn');
            const $form = $self.find('form');
            const $input = $form.find('input[type="text"]');

            $form.on('submit', () => {
                //trim first
                $input.val($input.val().trim());

                if($input.val() == 0){
                    $input.focus();
                    return false;
                }
            });

            $btn.on('click', (e) => {
                if($('body').hasClass('search')){
                    //focus input
                    $(window).trigger('fr:focus-search-bar');
                }else{
                    //open dropdown
                    toggleOpenDropdown($self, $btn, $input);
                }
            });

            $(document).on('click', (ev) => {
                if(!$(ev.target).closest('.general-search-container').length && $btn.hasClass('is--opened')){
                    $btn.removeClass('is--opened');
                    resetSearch($self);
                }
            });
		});
	}

	$(() => {
		$('.general-search-container').navSearchButton();
	});
})($);