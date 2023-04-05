<?php
    if(!class_exists('ColorSwatchShortcode')):
        // [rene-sola-color-swatch]
        class ColorSwatchShortcode {

            public static $shortcode_tag;

            public function __construct() {

                self::$shortcode_tag = 'rene-sola-color-swatch';

                add_action('init', [$this, 'ShortcodeInit']);
            }

            public function ShortcodeInit(){
                add_shortcode( self::$shortcode_tag, [$this, 'ShortcodeHandler'] );
            }

            public function ShortcodeHandler( $atts, $content = null, $tag ){
                // normalize attribute keys, lowercase
                $atts = array_change_key_case( (array)$atts, CASE_LOWER );
                
                extract(shortcode_atts(array(
                    'foo' => false
                ), $atts));

                $foo = trim($foo);

                return view('components.color-swatch');
            }

        }
        /**
         * Initialize class
         */
        global $ColorSwatchShortcode; $ColorSwatchShortcode = new \ColorSwatchShortcode();
    endif;