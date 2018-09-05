<?php

class SSM_Taxonomies extends SSM_Admin {
    
    /**
     * 
	 * It extracts the array of taxonomies, loop through each of them and register it to the corresponding cpt's.
	 *	
	 * @since    1.0.0
	 */
	public function register_taxonomies( $args ) {

		$defaults = array();

		$defaults['general'] = array(
			'hierarchical' 		=> FALSE,
			'show_ui' 			=> TRUE,
			'show_admin_column' => TRUE,
			'query_var' 		=> TRUE
		);

		foreach ( $args as $taxonomy ) {

			// Obligatory variables

			$tax_name 		= $taxonomy['tax_name']; 
			$cpt_name 		= $taxonomy['cpt_name'];
			$slug 			= $taxonomy['slug'];
			$text_domain 	= $taxonomy['text_domain'];
			$single 		= $taxonomy['single'];
			$plural 		= $taxonomy['plural'];

			// Optional variables

			$opts = array();

			foreach ( $defaults['general'] as $parameter => $value ) {
				$$parameter = ( isset( $taxonomy[ $parameter ] ) && !is_null( $taxonomy[ $parameter ] ) ) ? $taxonomy[ $parameter ] : $defaults['general'][ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$defaults['labels'] = array(
				'name'						=> esc_html__( $plural, $text_domain ),
				'singular_name'				=> esc_html__( $single, $text_domain ),
				'menu_name'					=> esc_html__( $plural, $text_domain ),
				'all_items'					=> esc_html__( $plural, $text_domain ),
				'edit_item'					=> esc_html__( "Edit {$single}", $text_domain ),
				'view_item'					=> esc_html__( "View {$single}", $text_domain ),
				'view_items'				=> esc_html__( "View {$plural}", $text_domain ),
				'update_item'				=> esc_html__( "Update {$single}", $text_domain ),
				'add_new'					=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'				=> esc_html__( "Add New {$single}", $text_domain ),
				'new_item_name'				=> esc_html__( "New {$single} name", $text_domain ),
				'parent_item'				=> esc_html__( "Parent {$single} :", $text_domain ),
				'parent_item_colon'			=> esc_html__( "Parent {$plural} :", $text_domain ),
				'search_items'				=> esc_html__( "Search {$plural}", $text_domain ),
				'popular_items'				=> esc_html__( "Popular {$plural}", $text_domain ),
				'separate_items_with_commas'=> esc_html__( "Separate {$plural} with commas", $text_domain ),
				'add_or_remove_items' 		=> esc_html__( "Add or remove {$plural}", $text_domain ),
				'choose_from_most_used'		=> esc_html__( "Choose from most used", $text_domain ),
				'not_found'					=> esc_html__( "No {$plural} Found", $text_domain ),
			);

			if ( isset( $taxonomy['labels'] ) ) {
				
				foreach ( $defaults['labels'] as $parameter => $value ) {
					$opts['labels'][ $parameter ] = !is_null( $taxonomy['labels'][ $parameter ] ) ? $taxonomy['labels'][ $parameter ] : $defaults['labels'][ $parameter ];
				}

			} else {
				$opts['labels'] = $defaults['labels'];
			}

			$defaults['capabilities'] = array(
				'manage_terms' 		=> "manage_categories",
				'edit_terms' 		=> "manage_categories",
				'delete_terms' 		=> "manage_categories",
				'assign_terms'		=> "edit_posts"
			);

			if ( isset( $taxonomy['capabilities'] ) ) {
				foreach ( $defaults['capabilities'] as $parameter => $value ) {
					$opts['capabilities'][ $parameter ] = !is_null( $taxonomy['capabilities'][ $parameter ] ) ? $taxonomy['capabilities'][ $parameter ] : $defaults['capabilities'][ $parameter ];
				}
			} else {
				$opts['capabilities'] = $defaults['capabilities'];
			}

			$defaults['rewrite'] = array(
				'ep_mask'			=> EP_PERMALINK,
				'feeds'				=> FALSE,
				'pages'				=> TRUE,
				'slug'				=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'		=> FALSE
			);

			if ( isset( $taxonomy['rewrite'] ) ) {
				foreach ( $defaults['rewrite'] as $parameter => $value ) {
					$opts['rewrite'][ $parameter ] = !is_null( $taxonomy['rewrite'][ $parameter ] ) ? $taxonomy['rewrite'][ $parameter ] : $defaults['rewrite'][ $parameter ];
				}
			} else {
				$opts['rewrite'] = $defaults['rewrite'];
			}

			$opts = apply_filters( "ssm-" . $slug . "-options", $opts );

			register_taxonomy( $tax_name, $cpt_name, $opts );

		}

	}

	/**
	 * It extracts the array of terms, loop through each of them and register it to the corresponding taxonomies.
	 *	
	 * @since    1.0.0
	 */
	public function register_terms( $args ) {

		$defaults = array(
			'alias_of' 		=> '',
			'description' 	=> '',
			'parent' 		=> 0
		);

		foreach ( $args as $term ) {

			// Obligatory variables

			$term_name 		= $term['term_name']; 
			$taxonomy_name 	= $term['taxonomy_name'];
			$slug 			= $term['slug'];

			// Optional variabls

			$opts = array();

			foreach ( $defaults as $parameter => $value ) {
				$$parameter = ( isset( $term[ $parameter ] ) && !is_null( $term[ $parameter ] ) ) ? $term[ $parameter ] : $defaults[ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$opts['slug'] = strtolower( $slug );

			$opts = apply_filters( "ssm-" . $slug . "-options", $opts );

			wp_insert_term( $term_name, $taxonomy_name, $opts );

		}

    }
    
    /**
	 * Prevent adding new terms to custom taxonomies.
	 *	
	 * @since    1.0.0
	 */
	function term_adding_prevent( $term, $taxonomy ) {

		$taxonomies = array( "test_type" ); //list of taxonomies we don't allow to add term into

		return ( in_array( $taxonomy, $taxonomies ) ) ? new WP_Error( 'term_addition_blocked', __( 'You cannot add terms to this taxonomy.' ) ) : $term;
		
	}

	/**
	 * Prevent removing terms from custom taxonomies.
	 *	
	 * @since    1.0.0
	 */
	function term_removing_prevent( $term_id ) {

		$terms = array();
		$taxonomies = array( "test_type" ); //list of taxonomies whose terms we don't allow to remove

		foreach ( $taxonomies as $taxonomy ) {
			$terms = array_merge( $terms, get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) );
		}

		foreach ( $terms as $term ) {
			if( get_term( $term_id )->slug === $term->slug ) 
				wp_die( 'You cannot remove terms from this taxonomy.' );
		}
	
	}

	/**
	 * Set default terms for custom taxonomies on post save.
	 *	
	 * @since    1.0.0
	 */
	function set_default_terms( $post_id, $post ) {

		if ( 'publish' === $post->post_status ) {

			$defaults = array(
				'test_type' 	=> array( 'test_type_term' ),
				// and so on...
			);

			$taxonomies = get_object_taxonomies( $post->post_type );

			foreach ( (array) $taxonomies as $taxonomy ) {

				$terms = wp_get_post_terms( $post_id, $taxonomy );
				
				if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
					wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
				}

			}
		}
	}


}