<?php

namespace SSM\Admin;

class CPT
{

	/**
	 * Register custom Post Types
	 */
    public function registerPostTypes()
    {
        register_extended_post_type( 'article', array(

            'capability_type'   => "page",
            'menu_icon'         => 'dashicons-format-aside',
            "menu_position"		=> 25,
            "supports" 			=> array( 'title', 'editor', 'thumbnail' ),

            'admin_cols'    => array( // admin posts list columns
                'category' => array(
                    'taxonomy'      => 'article_category'
                )
            ),

            'admin_filters' => array( // admin posts list filters
                'category' => array(
                    'taxonomy' => 'article_category'
                )
            )

        ), array(

            'singular'  => 'Article',
            'plural'    => 'Articles',
            'slug'      => 'article'

        ) );
    }

    /**
	 * Register custom Taxonomies
	 */
    public function registerTaxonomies()
    {
        register_extended_taxonomy( 'article_category', 'article', array(

            'hierarchical' => true

        ), array(

            'singular'  => 'Category',
            'plural'    => 'Categories',
            'slug'      => 'category'

        ) );

        register_extended_taxonomy( 'acf_category', 'acf-field-group', array(), array(

            'singular'  => 'Category',
            'plural'    => 'Categories',
            'slug'      => 'category'

        ) );
    
    }

    /**
	 * Register custom Terms
	 */
    public function registerTerms()
    {

        wp_insert_term('Modules', 'acf_category');
        wp_insert_term('Lists', 'acf_category');
        wp_insert_term('Components', 'acf_category');
        wp_insert_term('Options', 'acf_category');
        wp_insert_term('Module Lists', 'acf_category');
        wp_insert_term('Templates', 'acf_category');
        wp_insert_term('Page UI', 'acf_category');        
        wp_insert_term('Settings Page UI', 'acf_category');
    
    }

}