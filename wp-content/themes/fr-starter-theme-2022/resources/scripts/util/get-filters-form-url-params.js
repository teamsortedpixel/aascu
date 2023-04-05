(function($) {
    $.fn.frGetFiltersFormUrlParams = ($self) => {
        const serializedData = $self.find(':input')
            .filter(function(i, el) {
                return $(el).val().length !== 0 && $(el).val() != '_all';
            })
            .serialize();

        return serializedData;
    };
})($);