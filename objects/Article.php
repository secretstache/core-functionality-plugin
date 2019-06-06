<?php

namespace SSM\Objects;

class Article
{

    public function registerPostType()
    {
        register_extended_post_type( 'article', array(

            'capability_type'   => "page",
            'menu_icon'         => 'dashicons-format-aside',
            "menu_position"		=> 25,
            "supports" 			=> array( 'title', 'editor', 'thumbnail' ),
            "show_in_menu"      => 'ssm',
            "has_archive"       => 'articles',

            "labels" => array(
                "all_items" => "Articles"
            ),

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

    public function registerTaxonomies() {

        register_extended_taxonomy( 'article_category', 'article', array(

            'hierarchical' => true
            
        ), array(
            
            'singular'  => 'Category',
            'plural'    => 'Categories',
            'slug'      => 'article_category'
            
        ) );

    }
    
}