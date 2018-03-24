<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    SSM_Core_Functionality_Starter
 * @subpackage SSM_Core_Functionality_Starter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SSM_Core_Functionality_Starter
 * @subpackage SSM_Core_Functionality_Starter/admin
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class SSM_Core_Functionality_Starter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SSM_Core_Functionality_Starter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SSM_Core_Functionality_Starter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ssm-core-functionality-starter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SSM_Core_Functionality_Starter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SSM_Core_Functionality_Starter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ssm-core-functionality-starter-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This function is hooked to 'init'.
	 * Step by step, it adds items to main multidemensional array of post types,
	 * taxonomies and terms and call corresponding hooks using do_action().
	 *	
	 * @since    1.0.0
	 */
	public function call_registration_hook() {

		// Registration of CPT

		$cpt_args = array();

		array_push( $cpt_args, array(
			"cpt_name" 			=> "test",
			"slug" 				=> "ssm-test",
			"text_domain" 		=> "ssm-test",
			"single" 			=> "Test",
			"plural" 			=> "Tests",

			"capability_type" 	=> "page",
			"menu_icon"			=> "dashicons-admin-page",
			"menu_position"		=> 25,
			"show_in_menu"		=> TRUE,
			"supports" 			=> array( 'title', 'editor', 'thumbnail' )
		));

		// new post types go here...

		if ( !empty( $cpt_args ) ) {
			do_action( 'custom_cpt_hook', $cpt_args );
		}


		// Registration of Taxonomies

		$tax_args = array();

		array_push( $tax_args, array(
			"tax_name" 		=> "test_type",
			"cpt_name" 		=> "test",
			"slug" 			=> "ssm-test-type",
			"text_domain"	=> "ssm-test-type",
			"single" 		=> "Type",
			"plural" 		=> "Types",
			
			'hierarchical' 		=> TRUE
		) );
	
		// new taxonomies go here...

		if ( !empty( $tax_args ) ) {
			do_action( 'custom_taxonomies_hook', $tax_args );
		}


		// Registration of Terms

		$terms_args = array();

		array_push( $terms_args, array(
			"name" 		=> "Test",
			"taxonomy" 	=> "test_type",
			"args" => array(
				"slug" => "test"
			)
		) );
		
		// new terms go here...

		if ( !empty( $terms_args ) ) {
			do_action( 'custom_terms_hook', $terms_args );
		}

	}

	/**
	 * This function is fired after 'custom_registration_hook' was called.
	 * It extracts the array of cpt's, loop through each of them and register it.
	 *	
	 * @since    1.0.0
	 */
	public function register_post_types( $args ) {

		$defaults = array(
			'can_export'   			=> TRUE,
			'capability_type' 		=> "page",
			'description' 			=> '',
			'exclude_from_search' 	=> FALSE,
			'has_archive' 			=> FALSE,
			'hierarchical'	 		=> FALSE,
			'map_meta_cap'			=> TRUE,
			'menu_icon' 			=> 'dashicons-admin-page',
			'menu_position' 		=> 25,
			'public' 				=> FALSE,
			'publicly_querable' 	=> TRUE,
			'query_var' 			=> TRUE,
			'register_meta_box_cb'	=> '',
			'rewrite' 				=> FALSE,
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

			foreach ( $defaults as $parameter => $value ) {
				$$parameter = !is_null( $post_type[ $parameter ] ) ? $post_type[ $parameter ] : $defaults[ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$opts['capabilities'] = array(
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

			$opts['labels'] = array(
				'add_new'						=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'					=> esc_html__( "Add New {$single}", $text_domain ),
				'all_items'						=> esc_html__( $plural, $text_domain ),
				'edit_item'						=> esc_html__( "Edit {$single}", $text_domain ),
				'menu_name'						=> esc_html__( $plural, $text_domain ),
				'name'							=> esc_html__( $plural, $text_domain ),
				'name_admin_bar'				=> esc_html__( $single, $text_domain ),
				'new_item'						=> esc_html__( "New {$single}", $text_domain ),
				'not_found'						=> esc_html__( "No {$plural} Found", $text_domain ),
				'not_found_in_trash'			=> esc_html__( "No {$plural} Found in Trash", $text_domain ),
				'parent_item_colon'				=> esc_html__( "Parent {$plural} :", $text_domain ),
				'search_items'					=> esc_html__( "Search {$plural}", $text_domain ),
				'singular_name'					=> esc_html__( $single, $text_domain ),
				'view_item'						=> esc_html__( "View {$single}", $text_domain )
			);

			$opts['rewrite'] = array(
				'ep_mask'						=> EP_PERMALINK,
				'feeds'							=> FALSE,
				'pages'							=> TRUE,
				'slug'							=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'					=> FALSE
			);

			$opts = apply_filters( "ssm-" . $slug . "-cpt-options", $opts );

			register_post_type( strtolower( $cpt_name ), $opts );

		}

	}

	/**
	 * This function is fired after 'custom_taxonomies_hook' was called.
	 * It extracts the array of taxonomies, loop through each of them and register it to the corresponding cpt's.
	 *	
	 * @since    1.0.0
	 */
	public function register_taxonomies( $args ) {

		$defaults = array(
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

			foreach ( $defaults as $parameter => $value ) {
				$$parameter = !is_null( $taxonomy[ $parameter ] ) ? $taxonomy[ $parameter ] : $defaults[ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$opts['labels'] = array(
				'add_new'				=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'			=> esc_html__( "Add New {$single}", $text_domain ),
				'all_items'				=> esc_html__( $plural, $text_domain ),
				'edit_item'				=> esc_html__( "Edit {$single}", $text_domain ),
				'menu_name'				=> esc_html__( $plural, $text_domain ),
				'name'					=> esc_html__( $plural, $text_domain ),
				'name_admin_bar'		=> esc_html__( $single, $text_domain ),
				'new_item'				=> esc_html__( "New {$single}", $text_domain ),
				'not_found'				=> esc_html__( "No {$plural} Found", $text_domain ),
				'not_found_in_trash'	=> esc_html__( "No {$plural} Found in Trash", $text_domain ),
				'parent_item_colon'		=> esc_html__( "Parent {$plural} :", $text_domain ),
				'search_items'			=> esc_html__( "Search {$plural}", $text_domain ),
				'singular_name'			=> esc_html__( $single, $text_domain ),
				'view_item'				=> esc_html__( "View {$single}", $text_domain )
			);

			$opts['rewrite'] = array(
				'ep_mask'			=> EP_PERMALINK,
				'feeds'				=> FALSE,
				'pages'				=> TRUE,
				'slug'				=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'		=> FALSE
			);

			$opts = apply_filters( "ssm-" . $slug . "-options", $opts );

			register_taxonomy( $tax_name, $cpt_name, $opts );

		}

	}

	/**
	 * This function is fired after 'custom_terms_hook' was called.
	 * It extracts the array of terms, loop through each of them and register it to the corresponding taxonomies.
	 *	
	 * @since    1.0.0
	 */
	public function register_terms( $args ) {

		foreach ( $args as $term ) {

			$name 		= $term['name']; 
			$taxonomy 	= $term['taxonomy'];
			$args 		= $term['args'];

			wp_insert_term( $name, $taxonomy, $args );

		}

	}

}
