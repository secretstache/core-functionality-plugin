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
	 * This function is fired after 'custom_registration_hook' was called.
	 * It extracts the array of cpt's, loop through each of them and register it.
	 *	
	 * @since    1.0.0
	 */
	public function register_post_types( $args ) {

		foreach ( $args as $post_type ) {

			$cap_type 		= $post_type[ 'cap_type' ];
			$plural 		= $post_type[ 'plural' ];
			$single 		= $post_type[ 'single' ];
			$cpt_name 		= $post_type[ 'cpt_name' ];
			$slug			= $post_type[ 'slug' ];
			$text_domain 	= $post_type[ 'text_domain' ];
			$menu_icon		= $post_type[ 'menu_icon' ];

			$opts = array(
				'can_export' 					=> TRUE,
				'capability_type' 				=> $cap_type,
				'description' 					=> '',
				'exclude_from_search' 			=> FALSE,
				'has_archive' 					=> FALSE,
				'hierarchical'	 				=> FALSE,
				'map_meta_cap' 					=> TRUE,
				'menu_icon' 					=> $menu_icon,
				'menu_position' 				=> 25,
				'public' 						=> FALSE,
				'publicly_querable' 			=> TRUE,
				'query_var' 					=> TRUE,
				'register_meta_box_cb'			=> '',
				'rewrite' 						=> FALSE,
				'show_in_admin_bar'				=> TRUE,
				'show_in_menu'					=> TRUE,
				'show_in_nav_menu' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'supports' 						=> array( 'title', 'editor' ),
				'taxonomies' 					=> array(),
				'show_in_rest' 					=> TRUE
			);

			$opts['capabilities'] = array(
				'delete_others_posts'			=> "delete_others_{$cap_type}s",
				'delete_post'					=> "delete_{$cap_type}",
				'delete_posts'					=> "delete_{$cap_type}s",
				'delete_private_posts'			=> "delete_private_{$cap_type}s",
				'delete_published_posts'		=> "delete_published_{$cap_type}s",
				'edit_others_posts'				=> "edit_others_{$cap_type}s",
				'edit_post'						=> "edit_{$cap_type}",
				'edit_posts'					=> "edit_{$cap_type}s",
				'edit_private_posts'			=> "edit_private_{$cap_type}s",
				'edit_published_posts'			=> "edit_published_{$cap_type}s",
				'publish_posts'					=> "publish_{$cap_type}s",
				'read_post'						=> "read_{$cap_type}",
				'read_private_posts'			=> "read_private_{$cap_type}s"
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

			$opts = apply_filters( 'ssm-online-review-cpt-options', $opts );

			register_post_type( strtolower( $cpt_name ), $opts );

		}

	}

	/**
	 * We are calling this function after 'init' hook was fired.
	 * If required post_type is included using add_theme_support, it adds
	 * the appropriate multidemensional array to the main array of cpt's.
	 * After that 'custom_registration_hook' is calling with the corresponding arguments.
	 *	
	 * @since    1.0.0
	 */
	public function call_registration_hook() {

		$args = array();

		if ( current_theme_supports( 'ssm-team-cpt' ) ) {

			array_push( $args, array(
				"cap_type" 		=> "page",
				"plural" 		=> "Team Members",
				"single" 		=> "Team Member",
				"cpt_name" 		=> "team",
				"slug" 			=> "ssm-team",
				"text_domain" 	=> "ssm-team",
				"menu_icon"		=> "dashicons-businessman"
			));
		}

		if ( current_theme_supports( 'ssm-testimonial-cpt' ) ) {

			array_push( $args, array(
				"cap_type" 		=> "page",
				"plural" 		=> "Testimonials",
				"single" 		=> "Testimonial",
				"cpt_name" 		=> "testimonial",
				"slug" 			=> "ssm-testimonial",
				"text_domain" 	=> "ssm-testimonials",
				"menu_icon"		=> "dashicons-format-chat"
			));
		}

		if ( current_theme_supports( 'ssm-project-cpt' ) ) {

			array_push( $args, array(
				"cap_type" 		=> "page",
				"plural" 		=> "Projects",
				"single" 		=> "Projects",
				"cpt_name" 		=> "project",
				"slug" 			=> "ssm-project",
				"text_domain" 	=> "ssm-projects",
				"menu_icon"		=> "dashicons-portfolio"
			));
		}

		if ( current_theme_supports( 'ssm-code-snippet-cpt' ) ) {

			array_push( $args, array(
				"cap_type" 		=> "page",
				"plural" 		=> "Code Snippets",
				"single" 		=> "Code Snippet",
				"cpt_name" 		=> "code-snippet",
				"slug" 			=> "ssm-code-snippet",
				"text_domain" 	=> "ssm-code-snippets",
				"menu_icon"		=> "dashicons-format-quote"
			));
		}

		// new post type goes here...

		if ( !empty( $args ) ) {
			
			do_action( 'custom_registration_hook', $args );

		}

	}


}
