(function() {
    tinymce.PluginManager.add( window.cta_button_shortcode_vars.class_name, function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton(window.cta_button_shortcode_vars.class_name, {
            title: 'Insert CTA Button',
            text: 'CTA',
            classes: ' fff-custom-cta-button-class ', 
            cmd: window.cta_button_shortcode_vars.class_name,
        });
        
        // Add Command when Button Clicked
        editor.addCommand(window.cta_button_shortcode_vars.class_name, function() {
            
            var getStyleArray = function(styles){
                var result = [];
                styles.forEach(function(el) {
                    var label = el.replace(/-/g, ' ').replace(/_/g, ' ');
                    label = label.replace(/(^\w{1})|(\s{1}\w{1})/g, match => match.toUpperCase());
                    result.push({
                        text: label,
                        value: el,
                    });
                });
                return result;
            }

            var validateInputs = function(post_id_external_url_anchor, label){

                // Execute validation on data
                if(!label){
                    return {
                        message: 'Label is required',
                        isValid: false
                    };
                }

                if(!post_id_external_url_anchor){
                    return {
                        message: 'Post Id/Url/Anchor is required',
                        isValid: false
                    };
                }

                return {
                    message: '',
                    isValid: true
                };
            }

            var body = [
                {
                    type: 'textbox', 
                    name: 'label', 
                    label: 'Label',
                },
                {
                    type   : 'listbox',
                    name   : 'cta_type',
                    label  : 'CTA Type',
                    values : [
                        { text: 'Internal Post/Page', value: 'post_id' },
                        { text: 'External URL', value: 'external_url' },
                        { text: 'Anchor', value: 'anchor' },
                    ],
                    value : 'post_id', // Sets the default
                },
                {
                    type: 'textbox', 
                    name: 'post_id_external_url_anchor', 
                    label: 'Post ID/External Url/Anchor',
                    tooltip: 'For internal posts/pages enter Post/Page ID in the field. For external URL add the URL of the page you want to link to. For Anchor enter a word or two — without spaces — to go to the specific block/section of the page with the same anchor.',
                },
                {
                    type   : 'listbox',
                    name   : 'style',
                    label  : 'Style',
                    values : getStyleArray(window.cta_button_shortcode_vars.styles),
                    value : window.cta_button_shortcode_vars.styles[0], // Sets the default
                },
                {
                    type   : 'checkbox',
                    name   : 'new_tab',
                    label  : 'Open in new tab?',
                }
            ];
            
            editor.windowManager.open({
                title: 'Edit CTA Configuration',
                body: body,
                onsubmit: function(e) {
                    e.preventDefault();
                    
                    var post_id_external_url_anchor = e.data.post_id_external_url_anchor.replace(/(['"])/g, '\\$1');

                    var label = e.data.label.replace(/(['"])/g, '\\$1');

                    var type = e.data.open_video_modal ? 'external_url' : e.data.cta_type;

                    // Validate inputs
                    var validationResult = validateInputs(post_id_external_url_anchor, label);
                    if(!validationResult.isValid){
                        editor.windowManager.alert(validationResult.message);
                        return true;
                    }

                    var typeAttr = '';
                    if(type == 'post_id'){
                        typeAttr = 'post_id';
                    }else if(type == 'external_url'){
                        typeAttr = 'external_url';
                    }else if(type == 'anchor'){
                        typeAttr = 'anchor';
                        
                        //if client adds # by mistake, let's clean that too. Also let's trim the spaces to the left and right of value
                        post_id_external_url_anchor = post_id_external_url_anchor.replace('#', '').trim();
                    }

                    var shortcode = 'label="'+label+'" '+typeAttr+'="'+post_id_external_url_anchor+'" style="'+e.data.style+'"';
                    shortcode = window.cta_button_shortcode_vars.shortcode_tag + ' ' + shortcode;
                    shortcode += e.data.new_tab ? ' new_tab="true"' : ''; 
                    shortcode = '[' + shortcode + ']';

                    editor.execCommand('mceInsertContent', true, shortcode);
                    editor.windowManager.close();
                },
            });
        });
    });
})();