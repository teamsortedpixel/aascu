(function ($) {
	$.fn.searchBarComponent = function () {
		return this.each((i, el) => {
			const $self = $(el);
            const $form = $self.find('form');
            const $input = $form.find('input[type="text"]');
            const $submitBtn = $form.find('button.search-btn');

            $input.on('keyup', (e) => {
                $submitBtn.attr('disabled', $(e.currentTarget).val().length == 0);
				$input.parent().toggleClass('is-invalid', $(e.currentTarget).val().length == 0);
            });

			$form.on('submit', () => {
				//trim first
				$input.val($input.val().trim());
			});

			$(window).on('fr:focus-search-bar', () => {
				$input[0].focus();
			});
		});
	}

	$(() => {
		$('.search-bar-container').searchBarComponent();
	});
})($);