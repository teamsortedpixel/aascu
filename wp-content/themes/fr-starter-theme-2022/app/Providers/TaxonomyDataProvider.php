<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TaxonomyDataProvider extends ServiceProvider
{
	public function __construct()
    {
        
    }


    /**
     * Recursively get taxonomy and its children
     *
     * @param string $taxonomy
     * @param int $parent - parent term id
     * @return array
     */
    public static function GetWpTerms($taxonomy, $parent = 0)
    {
        // only 1 taxonomy
        $taxonomy = is_array($taxonomy) ? array_shift($taxonomy) : $taxonomy;
        // get all direct descendants of the $parent
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'parent' => $parent,
            'hide_empty' => false
        ]);
        // prepare a new array.  these are the children of $parent
        // we'll ultimately copy all the $terms into this new array, but only after they
        // find their own children
        $childrens = array();
        // go through all the direct descendants of $parent, and gather their children
        foreach ($terms as $term) {
            // recurse to get the direct descendants of "this" term\
            $childrens[$term->term_id] = [
                'term_id' => $term->term_id,
                'slug' => $term->slug,
                'name' => $term->name,
                'childrens' => self::GetWpTerms($taxonomy, $term->term_id)
            ];
        }
        // send the results back to the caller
        return $childrens;
    }

    /**
     * Get primary term
     *
     * @param int $post_id - post id 
     * @param string $taxonomy - taxonomy name
     * @return WP_Term object
     */
    public static function GetPrimaryTerm($post_id, $taxonomy)
    {
        // Get all terms first
        $terms = get_the_terms($post_id, $taxonomy);

        // If not terms selected 
        if(!$terms || count($terms) === 0){
            return false;
        }

        // Get primary term id of Yoast
        $primary_term_id = get_post_meta($post_id, '_yoast_wpseo_primary_'.$taxonomy, true);

        // If not terms selected 
        if(!$primary_term_id || $primary_term_id === ''){
            return $terms[0];
        }

        // Check each term
        foreach($terms as $term) {
            // Check this is a primary term
            if( (int)$primary_term_id === $term->term_id ) {
                return $term;
            }
        }

        // If not found primary term
        return $terms[0];
    }


    /**
     * Get primary term name
     *
     * @param int $post_id - post id 
     * @param string $taxonomy - taxonomy name
     * @return string term_name
     */
    public static function GetPrimaryTermName($post_id, $taxonomy)
    {
        // Get primary term
        $primary_term = self::GetPrimaryTerm($post_id, $taxonomy);

        return $primary_term ? $primary_term->name : false;
    }

    public static function GetWpPostTerms($taxonomy)
    {
        $result_terms = [];

        // Prepare args
        $args = [
            'post_type' => $taxonomy['taxonomy'],
            'post_status' => 'publish',
            'posts_per_page' => -1
        ];

        // Add meta query
        if ($taxonomy['meta_key']) {
            $args['meta_query'] = [
                'relation' => 'AND',
                [
                    'key' => $taxonomy['meta_key'],
                    'value' => $taxonomy['meta_value'],
                    'compare' => 'LIKE'
                ]
            ];
        }

        $posts = get_posts($args);

        foreach ($posts as $post) {
            $children_terms = [];

            // Check for meta key
            if ($taxonomy['meta_key']) {
                $meta_data = get_post_meta($post->ID, $taxonomy['meta_key']);
                if ($meta_data && !in_array($taxonomy['meta_value'], $meta_data[0])) {
                    continue;
                }
            }

            $result_terms[] = [
                'term_id' => $post->ID,
                'name' => $post->post_title,
                'childrens' => $children_terms
            ];
        }

        return $result_terms;
    }


    public static function GetTerms($taxonomy)
    {
        return self::GetWpTerms($taxonomy);
    }

    public static function GetTaxonomyTerms($taxonomy)
    {
        $terms = self::GetWpTerms($taxonomy);

        return array_reduce($terms, function($resultTerms, $term) {
            $resultTerms[$term['term_id']] = $term;
            return $resultTerms;
        }, []);
    }

    public static function GetFilteredTermsByBlockInfo($blockData)
    {
        return array_map(function($taxonomy) {
            if($taxonomy['show_custom_filters']){
                $taxonomy['terms'] = array_map(function($customFilter) {
                    return $customFilter->to_array();
                }, $taxonomy['custom_filters']?:[]);
            }
            else {
                $taxonomy['terms'] = self::GetTaxonomyTerms($taxonomy['taxonomy']);
            }
            return $taxonomy;
        }, isset($blockData['filter_taxonomies']) && is_array($blockData['filter_taxonomies']) ? $blockData['filter_taxonomies'] : []);
    }
}
