<?php

namespace SSM\Admin;

use SSM\Admin\Admin;

class CPT extends Admin
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

}