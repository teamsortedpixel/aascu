<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Field Type Settings
    |--------------------------------------------------------------------------
    |
    | Here you can set default field group and field type configuration that
    | is then merged with your field groups when they are composed.
    |
    | This allows you to avoid the repetitive process of setting common field
    | configuration such as `ui` on every `trueFalse` field or your
    | preferred `instruction_placement` on every `fieldGroup`.
    |
    | See: https://wpml.org/documentation/related-projects/translate-sites-built-with-acf/recommended-custom-fields-translation-preferences-for-acf-and-wpml/
    | For WPML: 2 - Translate, 1 - Copy, 3 - Copy Once
    |
    |
    */

    'defaults' => [
        'trueFalse' => ['ui' => 1, 'wpml_cf_preferences' => 1],
        'select' => ['ui' => 1, 'wpml_cf_preferences' => 3],
        'text' => ['wpml_cf_preferences' => 2],
        'textarea' => ['wpml_cf_preferences' => 2],
        'wysiwyg' => ['wpml_cf_preferences' => 2],
        'range' => ['wpml_cf_preferences' => 1],
        'email' => ['wpml_cf_preferences' => 1],
        'url' => ['wpml_cf_preferences' => 1],
        'password' => ['wpml_cf_preferences' => 1],
        'file' => ['wpml_cf_preferences' => 1],
        'image' => ['wpml_cf_preferences' => 1],
        'oembed' => ['wpml_cf_preferences' => 1],
        'gallery' => ['wpml_cf_preferences' => 1],
        'radio' => ['wpml_cf_preferences' => 3],
        'checkbox' => ['wpml_cf_preferences' => 3],
        'buttonGroup' => ['wpml_cf_preferences' => 1],
        'repeater' => ['wpml_cf_preferences' => 3],
        'group' => ['wpml_cf_preferences' => 1],
        'link' => ['wpml_cf_preferences' => 1],
        'postObject' => ['wpml_cf_preferences' => 1],
        'taxonomy' => ['wpml_cf_preferences' => 1],
        'user' => ['wpml_cf_preferences' => 1],
    ],
];
