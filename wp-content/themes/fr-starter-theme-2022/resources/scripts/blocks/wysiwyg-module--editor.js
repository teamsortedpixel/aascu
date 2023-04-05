(function($) {
    if(acf){
        acf.addAction('load', function(){
            acf.addAction('append', function(){
                var widthFields = acf.getFields({
                    name: 'wc_width'
                });
    
                widthFields.forEach(function(field) {
                    if(field.$el.length && !$(field.$el).hasClass('fr--initialize')){
                        var $parent = $(field.$el).closest('.acf-row[data-id]');
                        var $options = $(field.$el).find('.acf-button-group > label > input');
    
                        $options.on('change fr:page-load', function(){
                            $.each($options, function (i, opt) { 
                                if($(opt).is(':checked')){
                                    var selectedVal = $(opt).val();
                                    $parent
                                        .removeClass('fr--width-1_4 fr--width-2_4 fr--width-3_4 fr--width-4_4 fr--width-auto')
                                        .addClass('fr--width-' + selectedVal);
                                }
                            });
                        });
                        $options.trigger('fr:page-load');
                    }
                    $(field.$el).addClass('fr--initialize');
                });
            });
        });
    }
})(jQuery);