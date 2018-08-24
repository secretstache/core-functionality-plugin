<?php

class SSM_Core_Functionality_CPT extends SSM_Core_Functionality_Admin {

    /**
	 * It extracts the array of cpt's, loop through each of them and register it.
	 *	
	 * @since    1.0.0
	 */
	public function register_post_types( $args ) {

		$defaults = array();

		$defaults['general'] = array(
			'can_export'   			=> TRUE,
			'capability_type' 		=> "page",
			'description' 			=> '',
			'exclude_from_search' 	=> FALSE,
			'has_archive' 			=> TRUE,
			'hierarchical'	 		=> FALSE,
			'map_meta_cap'			=> TRUE,
			'menu_icon' 			=> 'dashicons-admin-page',
			'menu_position' 		=> 25,
			'public' 				=> TRUE,
			'publicly_querable' 	=> TRUE,
			'query_var' 			=> TRUE,
			'register_meta_box_cb'	=> '',
			'rewrite' 				=> TRUE,
			'show_in_admin_bar'		=> TRUE,
			'show_in_menu'			=> TRUE,
			'show_in_nav_menu' 		=> TRUE,
			'show_ui' 				=> TRUE,
			'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
			'taxonomies' 			=> array(),
			'show_in_rest' 			=> TRUE
		);
		

		foreach ( $args as $post_type ) {

			// Obligatory variables

			$cpt_name 		= $post_type[ 'cpt_name' ];
			$slug			= $post_type[ 'slug' ];
			$text_domain 	= $post_type[ 'text_domain' ];
			$single 		= $post_type[ 'single' ];
			$plural 		= $post_type[ 'plural' ];
			
			// Optional variables

			$opts = array();

			foreach ( $defaults['general'] as $parameter => $value ) {
				$$parameter = ( isset( $post_type[ $parameter] ) && !is_null( $post_type[ $parameter ] ) ) ? $post_type[ $parameter ] : $defaults['general'][ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$defaults['labels'] = array(
				'name'					=> esc_html__( $plural, $text_domain ),
				'singular_name'			=> esc_html__( $single, $text_domain ),
				'add_new'				=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'			=> esc_html__( "Add New {$single}", $text_domain ),
				'edit_item'				=> esc_html__( "Edit {$single}", $text_domain ),
				'new_item'				=> esc_html__( "New {$single}", $text_domain ),
				'view_item'				=> esc_html__( "View {$single}", $text_domain ),
				'view_items'			=> esc_html__( "View {$plural}", $text_domain ),
				'search_items'			=> esc_html__( "Search {$plural}", $text_domain ),
				'not_found'				=> esc_html__( "No {$plural} Found", $text_domain ),
				'not_found_in_trash'	=> esc_html__( "No {$plural} Found in Trash", $text_domain ),
				'parent_item_colon'		=> esc_html__( "Parent {$plural} :", $text_domain ),
				'all_items'				=> esc_html__( $plural, $text_domain ),
				'archives'				=> esc_html__( "{$single} Archives", $text_domain ),
				'attributes' 			=> esc_html__( "{$single} Attributes", $text_domain ),
				'insert_into_item'		=> esc_html__( "Insert into {$single}", $text_domain ),
				'uploaded_to_this_item' => esc_html__( "Uploaded to this {$single}", $text_domain ),
				'featured_image'		=> esc_html__( "Featured image", $text_domain ),
				'set_featured_image'	=> esc_html__( "Set featured image for this", $text_domain ),
				'remove_featured_image' => esc_html__( "Remove featured image", $text_domain ),
				'use_featured_image'	=> esc_html__( "Use as featured image", $text_domain ),
				'menu_name'				=> esc_html__( $plural, $text_domain ),
				'filter_items_list'		=> esc_html__( "Filter {$plural} list", $text_domain ),
				'items_list_navigation' => esc_html__( "{$plural} list navigation", $text_domain ),
				'items_list'			=> esc_html__( "{$plural} list", $text_domain ),
				'name_admin_bar'		=> esc_html__( $single, $text_domain )
			);

			if ( isset( $post_type['labels'] ) ) {
				foreach ( $defaults['labels'] as $parameter => $value ) {
					$opts['labels'][ $parameter ] = !is_null( $post_type['labels'][ $parameter ] ) ? $post_type['labels'][ $parameter ] : $defaults['labels'][ $parameter ];
				}
			} else {
				$opts['labels'] = $defaults['labels'];
			}

			$defaults['capabilities'] = array(
				'delete_others_posts'			=> "delete_others_{$capability_type}s",
				'delete_post'					=> "delete_{$capability_type}",
				'delete_posts'					=> "delete_{$capability_type}s",
				'delete_private_posts'			=> "delete_private_{$capability_type}s",
				'delete_published_posts'		=> "delete_published_{$capability_type}s",
				'edit_others_posts'				=> "edit_others_{$capability_type}s",
				'edit_post'						=> "edit_{$capability_type}",
				'edit_posts'					=> "edit_{$capability_type}s",
				'edit_private_posts'			=> "edit_private_{$capability_type}s",
				'edit_published_posts'			=> "edit_published_{$capability_type}s",
				'publish_posts'					=> "publish_{$capability_type}s",
				'read_post'						=> "read_{$capability_type}",
				'read_private_posts'			=> "read_private_{$capability_type}s"
			);

			if ( isset( $post_type['capabilities'] ) ) {
				foreach ( $defaults['capabilities'] as $parameter => $value ) {
					$opts['capabilities'][ $parameter ] = !is_null( $post_type['capabilities'][ $parameter ] ) ? $post_type['capabilities'][ $parameter ] : $defaults['capabilities'][ $parameter ];
				}
			} else {
				$opts['capabilities'] = $defaults['capabilities'];
			}

			$defaults['rewrite'] = array(
				'ep_mask'						=> EP_PERMALINK,
				'feeds'							=> FALSE,
				'pages'							=> TRUE,
				'slug'							=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'					=> FALSE
			);

			if ( isset( $post_type['rewrite'] ) ) {
				foreach ( $defaults['rewrite'] as $parameter => $value ) {
					$opts['rewrite'][ $parameter ] = !is_null( $post_type['rewrite'][ $parameter ] ) ? $post_type['rewrite'][ $parameter ] : $defaults['rewrite'][ $parameter ];
				}
			} else {
				$opts['rewrite'] = $defaults['rewrite'];
			}

			$opts = apply_filters( "ssm-" . $slug . "-cpt-options", $opts );

			register_post_type( strtolower( $cpt_name ), $opts );

		}

	}


}