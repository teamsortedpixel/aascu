<?php
    if(!class_exists('CtaButtonShortcode')):
        // [cta-button label, external_url, post_id, style]
        class CtaButtonShortcode {

            public static $shortcode_tag;
            public static $class_name;
            public static $button_class_name;
            public static $styles;
            public static $aag_roles;

            public function __construct() {

                self::$shortcode_tag = 'cta-button';
                self::$class_name = 'fr_cta_button';
                self::$button_class_name = 'fff-custom-cta-button-class';
                self::$styles = [
                    'primary',
                    'secondary',
                    'tertiary'
                ];

                add_action('init', [$this, 'ShortcodeInit']);
                
                //set up tinyMCE button
                if(is_admin()){
                    add_action( 'init', [$this, 'SetUpMCEButton'] );
                }
            }

            public function ShortcodeInit(){
                add_shortcode( self::$shortcode_tag, [$this, 'ShortcodeHandler'] );
            }

            public function ShortcodeHandler( $atts, $content = null, $tag ){
                // normalize attribute keys, lowercase
                $atts = array_change_key_case( (array)$atts, CASE_LOWER );
                
                extract(shortcode_atts(array(
                    'external_url' => false,
                    'label' => '',
                    'post_id' => null,
                    'style' => '',
                    'role' => false,
                    'anchor' => false,
                    'new_tab' => false,
                ), $atts));

                $external_url = trim($external_url);
                $label = trim($label);
                $post_id = trim($post_id);
                $style = trim($style);
                $new_tab = trim($new_tab);
                $anchor = trim(str_replace('#', '', $anchor));

                // Because of style changes support for old data entries
                $style = trim(str_replace(['light', 'dark'], '', $style));
                //Data parsing
                if(in_array($style, self::$styles) == FALSE){
                    $style = self::$styles[0];
                }

                $target = $new_tab && $new_tab == 'true' ? '_blank' : '';

                $url = $external_url && strlen($external_url) > 0 ? htmlspecialchars_decode($external_url) : ($post_id !== null ? get_permalink($post_id) : ''); 

                //Anchor option
                if($anchor){
                    $url = '#' . $anchor;
                    $target = '';
                }

                //Create output
                if($style !== 'regular-link'){
                    $style = 'cta-button ' . $style;
                }

                return view('components.cta-button', [
                    'preview' => false,
                    'label' => $label,
                    'url' => $url,
                    'target' => $target,
                    'extra_atts' => isset($extra_attr) ? $extra_attr : '',
                    'style' => $style,
                    'arrow' => true
                ]);
            }

            public function SetUpMCEButton(){
                // Check if the logged in WordPress User can edit Posts or Pages
                // If not, don't register our TinyMCE plugin
                if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
                    return;
                }

                // Check if the logged in WordPress User has the Visual Editor enabled
                // If not, don't register our TinyMCE plugin
                if ( get_user_option( 'rich_editing' ) !== 'true' ) {
                    return;
                }

                // Setup some filters
                add_filter( 'mce_external_plugins', [$this, 'AddPlugin'] );
                add_filter( 'mce_buttons', [$this, 'AddToolbarButton'] );

                //Add CSS to make button look nice
                add_action('admin_head', function () {
                    $styles = '<style>
                        .fff-hide-cta-button .mce-btn.mce-[button_class_name]{
                            display:none;
                        }
                        .mce-toolbar .mce-btn-group .mce-btn.mce-[button_class_name]{
                            color: #0071a1;
                            border-color: #0071a1;
                            background: #f3f5f6;
                        }
                        .mce-toolbar .mce-btn-group .mce-btn.mce-[button_class_name] button{
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 6px;
                            font-size: 10px;
                        }
                        .mce-toolbar .mce-btn-group .mce-btn.mce-[button_class_name] .mce-txt {
                            color: #0071a1;
                        }
                    </style>';

                    $styles = str_replace('[button_class_name]', self::$button_class_name, $styles);
                    echo $styles;
                });

                //ADD JS VARS
                add_action('admin_head', function(){
                    $params = [
                        'class_name' => self::$class_name,
                        'shortcode_tag' => self::$shortcode_tag,
                        'styles' => self::$styles,
                    ];
                    echo 
                        '<script>
                            window.cta_button_shortcode_vars = JSON.parse(\''.json_encode($params, JSON_HEX_APOS).'\');
                        </script>';
                });
            }

            public function AddPlugin($plugin_array){
                $plugin_array[self::$class_name] = get_theme_file_uri() . '/app/Shortcodes/CtaButton/cta-button-class.js?ver=88e8';
                return $plugin_array;
            }

            public function AddToolbarButton($buttons){
                array_push( $buttons, self::$class_name );
                return $buttons;
            }
        }
        /**
         * Initialize class
         */
        global $CtaButtonShortcode; $CtaButtonShortcode = new \CtaButtonShortcode();
    endif;