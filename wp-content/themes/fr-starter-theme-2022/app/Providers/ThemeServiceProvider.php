<?php

namespace App\Providers;

use Roots\Acorn\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        add_action('admin_head', '\\App\Providers\ThemeServiceProvider::CustomAdminStyles');
        add_action('acf/input/admin_footer', '\\App\Providers\ThemeServiceProvider::CustomAcfJs');

        add_action('wp_head', '\\App\Providers\ThemeServiceProvider::AddGoogleCodeSnippedHeader', 100);
        add_action('wp_body_open', '\\App\Providers\ThemeServiceProvider::AddGoogleCodeAdditionalSnippedBody', 10);

        //This adds Jquery to frontend
        add_filter( 'wp_enqueue_scripts', '\\App\Providers\ThemeServiceProvider::addJquery' );

        //TEMPORARY UNTIL I FIGURE OUT WPENGINE CACHE STUFF
        add_action('init', '\\App\Providers\PostSearchProvider::SetAjaxActions', 20);
        add_action('init', '\\App\Providers\FilterableContentProvider::SetAjaxActions', 20);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public static function CustomAdminStyles(){ ?>
        <style>
            /*
            * ACF "repeat-horizontal" class, display repeaters in horizontal columns
            */
            .repeat-horizontal .acf-repeater tbody {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
            }
            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone) {
                width: 100%;
                flex-grow: 1;
                flex-shrink: 0;
                flex-basis: 21%; /* 21% because 25% gives CSS bugs when content pushes width and not 20 because we want the max to be 4 */
                border-bottom: 1px solid #eee;
            }

            .repeat-horizontal.cards-33 .acf-repeater tr.acf-row:not(.acf-clone) {
                flex-basis: 33%;
            }

            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone).fr--width-1_4 {
                flex: 0 0 25%;
            }
            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone).fr--width-2_4 {
                flex: 0 0 50%;
            }
            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone).fr--width-3_4 {
                flex: 0 0 75%;
            }
            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone).fr--width-4_4 {
                flex: 0 0 100%;
            }
            .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
                width: 100% !important; /* important is necessary because it gets overwritten on drag&drop  */
            }
            .repeat-horizontal .acf-repeater .acf-row-handle,
            .repeat-horizontal .acf-repeater .acf-fields{
                border-width: 0px 0px 0px 1px;
            }
            .repeat-horizontal .acf-repeater .acf-row-handle.order{
                min-width: 10px; /* to stop breaking layout if we keep the max rows bellow 10 */
            }
            .repeat-horizontal .acf-repeater .acf-row:last-child .acf-row-handle{
                border-width: 0px;
            }
            .repeat-horizontal .acf-repeater .acf-row-handle .acf-icon{
                position: relative;
                margin: 10px 0;
            }
            .repeat-horizontal .acf-repeater .acf-row:hover .acf-row-handle .acf-icon{
                display: none; /* remove standard annoying hover */
            }
            .repeat-horizontal .acf-repeater .acf-row-handle.remove:hover .acf-icon{
                display: block; /* re-apply hover on set block */
            }

            .acf-field-horizontal.acf-field {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
            }
            .acf-field-tiny-text.acf-field {
                font-size: 11px;
            }
            .acf-field-horizontal.acf-field.acf-field .acf-label {
                margin: 0;
                margin-right: 10px;
            }
            .acf-field-no-label.acf-field .acf-label {
                display: none;
            }
            .acf-no-label > .acf-label{
                display: none;
            }
            .menu-item-edit-active.fr-menu-item-panel-max-width-100 > .menu-item-bar .menu-item-handle,
            .menu-item-edit-active.fr-menu-item-panel-max-width-100 > .menu-item-settings{
                max-width: 100%;
            }
            .edit-post-meta-boxes-area .postbox>.inside ul.categorychecklist{
                padding-left: 0;
            }
            .acf-dimensions__buttons >ul > :nth-child(2) {
                display:none;
            }
            .acf-field-fr-bordered {
                border: 1px solid var(--bs-gray-500) !important;
                border-radius: 5px;
                padding: 10px !important;
            }
            .acf-field-fr-bordered > .acf-label{
                margin-bottom: 12px !important;
            }
            .acf-field-fr-bordered > .acf-label > label{
                font-weight: bold !important;
            }
            .fr-custom-decoration .acf-dimensions .acf-dimensions__inputs{
                align-items: flex-start;
                display: flex;
                margin-right: 0;
                flex-direction: column;
            }
            .fr-custom-decoration .acf-dimensions .acf-dimensions__texts {
                display: flex;
                flex-direction: row;
                width: 100%;
                flex-wrap: wrap;
                gap: 4px;
            }
            .fr-custom-decoration .acf-dimensions .acf-dimensions__input{
                display: flex;
                flex-direction: column;
                width: calc(50% - 2px);
            }
            .fr-custom-decoration .acf-dimensions .acf-dimensions__texts input[type=text]{
                border-right-width: 1px;
                max-width: 100%;
            }
            /* HIDE BROKEN PATTERN PREVIEWS */
            .block-pattern-explorer .block-pattern-explorer__preview-pattern-list__item-preview{
                display: none;
            }
            .block-editor-block-patterns-list__item .block-editor-block-preview__container{
                height: 1px;
            }
            .block-editor-block-patterns-list__item .block-editor-block-patterns-list__item-title{
                text-align: left;
            }

            body.wp-admin:not(.post-new-php) .fr-no-edit-input > .acf-input > .acf-input-wrap > input{
                background: lightgray;
                pointer-events: none;
            }

            /* HIDE DEFAULT WP METABOXES TO REPLACE THEM WITH THE ACF FIELDS I CREATE TO DO CUSTOM VALIDATION ON TAXONOMIES */
            #resource-typediv.postbox, #news-typediv.postbox, #formatdiv.postbox{
                display: none !important;
            }
        </style>
    <?php
    }

    public static function CustomAcfJs(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var initializeFields = function(fields){
                    fields.forEach(field => {
                        if(!jQuery(field.$el).hasClass('fr--initialized')){
                            field.on('change', function(){
                                jQuery(field.$el).closest('.menu-item').toggleClass('fr-menu-item-panel-max-width-100', field.val());
                            });
                            
                            //on page loaded
                            jQuery(field.$el).closest('.menu-item').toggleClass('fr-menu-item-panel-max-width-100', field.val());

                            jQuery(field.$el).addClass('fr--initialized');
                        }
                    });
                }

                if(window.acf){
                    acf.addAction('load', function(){
                        initializeFields(acf.getFields({
                            name: 'menu_item_enable_mega_menu_panel'
                        }));
                    });
                    acf.addAction('append', function(){
                        initializeFields(acf.getFields({
                            name: 'menu_item_enable_mega_menu_panel'
                        }));
                    });
                }
            });
        </script>
        <?php
    }

    public static function addJquery(){
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', [], '3.6.0', true);
    }

    public static function AddMegaMenuClassToNavItems($items, $args){
        if($args->theme_location !== 'primary_navigation') return $items;

        foreach($items as &$item){
            if(get_field('menu_item_enable_mega_menu_panel', $item)){
                $item->classes[] = 'fr-has-mm';
            }
        }

        return $items;
    }

    public static function AddGoogleCodeSnippedHeader(){
        if(get_field('google_tag_manager_code_snippet', 'option')){
            echo get_field('google_tag_manager_code_snippet', 'option');
        }
    }

    public static function AddGoogleCodeAdditionalSnippedBody(){
        if(get_field('additional_google_tag_manager_code_snippet', 'option')){
            echo get_field('additional_google_tag_manager_code_snippet', 'option');
        }
    }
}