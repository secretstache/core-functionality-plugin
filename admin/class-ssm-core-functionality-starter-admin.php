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
	public function __construct( $plugin_name, $version, $plugin_root_dir ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_root_dir = $plugin_root_dir;

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

	/* Immutable functions */

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

	/**
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

	/* Mutable functions */

	/**
	 * Step by step, it adds items to main multidemensional array of post types,
	 * taxonomies and terms and call corresponding hooks using do_action().
	 *	
	 * @since    1.0.0
	 */
	public function call_registration() {

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
			"supports" 			=> array( 'title', 'editor', 'thumbnail' ),

			"labels" 			=> array(
				'set_featured_image'	=> "Another formulation of Set featured image",
				'remove_featured_image' => "Another formulation of Remove featured image"
			),
			"capabilities"		=> array(
				'delete_others_posts' 	=> "delete_others_posts"
			),
			"rewrite" 			=> array(
				'with_front'			=> TRUE
			)
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
			
			'hierarchical' 		=> TRUE,

			"labels" 			=> array(
				'add_new_item'	=> "Another formulation of Add new Type",
			),
			"capabilities"		=> array(
				'assign_terms'			=> "edit_pages"
			),
			"rewrite" 			=> array(
				'with_front'			=> TRUE
			)
		) );
	
		// new taxonomies go here...

		if ( !empty( $tax_args ) ) {
			do_action( 'custom_taxonomies_hook', $tax_args );
		}


		// Registration of Terms

		$terms_args = array ();

		array_push( $terms_args, array(
			"term_name" 		=> "Term",
			"taxonomy_name" 	=> "test_type",
			"slug"				=> "test_type_term",

			"description" 		=> "Test description"
		) );
		
		// new terms go here...

		if ( !empty( $terms_args ) ) {
			do_action( 'custom_terms_hook', $terms_args );
		}

	}

	/**
	 * Set up list of required plugins with corresponding options
	*/
	function check_for_required_plugins() {

		$plugins_args = array();

		// array_push( $plugins_args, array(
		// 	"plugin_name"	=> "Radio Buttons for Taxonomies",
		// 	"plugin_slug"	=> "radio-buttons-for-taxonomies/radio-buttons-for-taxonomies.php",
		// 	"type"			=> "error",
		// 	"plugin_link" 	=> "https://wordpress.org/plugins/radio-buttons-for-taxonomies/"
		// ) );

		// array_push( $plugins_args, array(
		// 	"plugin_name"	=> "Gutenberg",
		// 	"plugin_slug"	=> "gutenberg/gutenberg.php",
		// 	"type"			=> "error",
		// 	"plugin_link" 	=> "https://wordpress.org/plugins/gutenberg/"
		// ) );

		// array_push( $plugins_args,  array(
		// 	"plugin_name"	=> "Akismet",
		// 	"plugin_slug" 	=> "akismet/akismet.php",
		// 	"type"			=> "warning",
		// 	"plugin_link"	=> "https://wordpress.org/plugins/akismet/"
		// ) );

		// new plugins go here...

		if ( !empty( $plugins_args ) ) {
			do_action( 'custom_required_plugins_hook', $plugins_args );
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

	/**
	 *
	 * This function is checking whether the required plugins was installed and are active.
	 *
	 * 
	 * @since    1.0.0
	 */
	public function check_required_plugins( $args ) {

		$plugin_domain = get_plugin_data( plugin_dir_path(__FILE__) )['TextDomain'];
		$plugin_name = "{$plugin_domain}/{$plugin_domain}.php"; 

		if ( !empty( $args ) ) {

			foreach ( $args as $arg ) {

				if ( is_admin() && current_user_can( 'activate_plugins' ) && ( !is_plugin_active( $arg['plugin_slug'] ) ) ) {
					
					if ( $arg['type'] == "error" ) {

						deactivate_plugins( $plugin_name ); 
			
						if ( isset( $_GET['activate'] ) ) {
							unset( $_GET['activate'] );
						}

						echo "<div class='notice notice-error'><p>Plugin cannot be activated, it <b>requires</b> <a href='" . $arg['plugin_link'] . "' target='_blank'>" . $arg['plugin_name'] .  "</a> plugin to be installed and active. You can install it directly from the <a href='/wp-admin/plugin-install.php?tab=search&s={$arg['plugin_name']}' target='_blank'>Plugin Directory</a></p></div>";
					
					} else {
						
						echo "<div class=' notice notice-warning'><p>It is <b>recommended</b> to install and activate <a href='" . $arg['plugin_link'] . "' target='_blank'>" . $arg['plugin_name'] . "</a> plugin. You can install it directly from the <a href='/wp-admin/plugin-install.php?tab=search&s={$arg['plugin_name']}' target='_blank'>Plugin Directory</a></p></div>";
					
					}
			
				}
			}
		}

	}


	/* Inhereted from ssm-core */

	/**
	 * Hide Advanced Custom Fields to Users
	 * @since 1.0.0
	 */
	public function remove_acf_menu() {

		// provide a list of usernames who can edit custom field definitions here
		$acfAdmins = get_option('ssm_core_acf_admin_users') != NULL ? get_option('ssm_core_acf_admin_users') : array(1);
	
		// get the current user
		$current_user = wp_get_current_user();
	
		if ( $acfAdmins != NULL ) {
	
		// match and remove if needed
		if ( !in_array( $current_user->ID, $acfAdmins ) ) {
			remove_menu_page('edit.php?post_type=acf-field-group');
		}
	
		}
	}

	/**
	 * Makes the login screen's logo link to your homepage, instead of to WordPress.org.
	 * @since 1.0.0
	 */
	public function login_headerurl() {
		return home_url();
	}

	/**
	 * Makes the login screen's logo title attribute your site title, instead of 'WordPress'.
	 * @since 1.0.0
	 */
	public function login_headertitle() {
		return get_bloginfo( 'name' );
	}

	/**
	 * Makes WordPress-generated emails appear 'from' your WordPress site name, instead of from 'WordPress'.
	 * @since 1.0.0
	 */
	public function mail_from_name() {
		return get_option( 'blogname' );
	}

	/**
	 * Makes WordPress-generated emails appear 'from' your WordPress admin email address.
	 * Disabled by default, in case you don't want to reveal your admin email.
	 * @since 1.0.0
	 */
	public function wp_mail_from() {
		return get_option( 'admin_email' );
	}

	/**
	 * Removes the WP icon from the admin bar
	 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
	 * @since 1.0.0
	 */
	public function remove_wp_icon_from_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	/**
	 * Modify the admin footer text
	 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
	 * @since 1.0.0
	 */
	function admin_footer_text() {

		$footer_text = get_option('ssm_core_agency_name') != NULL ? get_option('ssm_core_agency_name') : 'Secret Stache Media';
		$footer_link = get_option('ssm_core_agency_url') != NULL ? get_option('ssm_core_agency_url') : 'http://secretstache.com';

		echo 'Built by <a href="' . $footer_link . '" target="_blank">' . $footer_text . '</a> with WordPress.';
	}

	/**
	 * Remove Unnecessary User Roles
	 * @since 1.0.0
	 */

	public function remove_roles() {

		remove_role( 'subscriber' );
		remove_role( 'contributor' );
	
	}

	/**
	 * Remove default link for images
	 * @since 1.0.0
	 */
	public function remove_image_link() {

		$image_set = get_option( 'image_default_link_type' );
		
		if ($image_set !== 'none') {
			update_option('image_default_link_type', 'none');
		}
	}

	/**
	 * Show Kitchen Sink in WYSIWYG Editor by default
	 * @since 1.0.0
	 */
	public function show_kitchen_sink( $args ) {
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}

	/**
	 * Disable unused widgets.
	 * @since 1.0.0
	 */
	public function remove_widgets() {

		unregister_widget( 'WP_Widget_Pages' );
		unregister_widget( 'WP_Widget_Calendar' );
		// unregister_widget( 'WP_Widget_Archives' );
		unregister_widget( 'WP_Widget_Meta' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_RSS' );
		unregister_widget( 'WP_Widget_Tag_Cloud' );
	
	}

	/**
	 * Modifies the TinyMCE settings array
	 * @since 1.0.0
	 */
	public function update_tiny_mce( $init ) {

		$init['block_formats'] = 'Paragraph=p;Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote';
		return $init;
	
	}

	/**
	 * Remove <p> tags from around images
	 * See: http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	 * @since 1.0.0
	 */
	public function remove_ptags_on_images( $content ) {

		return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	
	}

	/**
	 * Remove the injected styles for the [gallery] shortcode
	 * @since 1.0.0
	 */
	public function remove_gallery_styles( $css ) {

		return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	
	}

	/**
	* Set Home Page Programmatically if a Page Called "Home" Exists
	* @since 1.0.0
	*/
	public function force_homepage() {
		$homepage = get_page_by_title( 'Home' );
	
		if ( $homepage ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );
		}
	}

	/**
	* Removes unnecessary menu items from add new dropdown
	* @since 1.0.0
	*/
	public function remove_wp_nodes() {
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_node( 'new-link' );
		$wp_admin_bar->remove_node( 'new-media' );
		$wp_admin_bar->remove_node( 'new-user' );
	}
	
	/**
	 * Filter Yoast SEO Metabox Priority
	 * @since 1.0.0
	 */
	public function yoast_seo_metabox_priority() {
		return 'low';
	}

	/**
	 * Add SSM widget to the dashboard.
	 */
	public function hosting_dashboard_widget() {

		wp_add_dashboard_widget(
			'ssm_main_dashboard_widget', // Widget slug.
			'Managed Hosting by Secret Stache Media', // Title.
			array( $this, 'hosting_widget_function') // Display function.
		);  
	}
	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	public function hosting_widget_function() {
	
		$html = '<p>As a customer of our managed hosting service, you can rest assured that your software is kept up to date and served on the best hosting technology available.</p>';
		$html .= '<p>You are also covered by our <strong>Code Warantee</strong>, so if you see something that doesn\'t seem right, feel free to <a href="mailto:help@secretstache.com">reach out</a>.';
	
		echo $html;
	
	}

	public function ssm_core_settings() {

		register_setting( 'ssm-core-settings-group', 'ssm_core_acf_admin_users' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_name' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_url' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_width' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_height' );
	
	
		if ( current_theme_supports( 'ssm-admin-branding' ) ) {
			add_settings_section( 'ssm-core-agency-options', 'Agency Options', array( $this, 'ssm_core_agency_options'), 'ssm_core');
		}
	
		add_settings_field( 'ssm-core-agency-name', 'Agency Name', array( $this, 'ssm_core_agency_name' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-agency-url', 'Agency URL', array( $this, 'ssm_core_agency_url' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-login-logo', 'Login Logo', array( $this, 'ssm_core_login_logo' ), 'ssm_core', 'ssm-core-agency-options' );
	
		
		if ( current_theme_supports( 'ssm-acf' ) ) {
			add_settings_section( 'ssm-core-acf-options', 'ACF Options', array( $this,  'ssm_acf_options' ), 'ssm_core' );
		}
	
		add_settings_field(
			'ssm-core-acf-admin-users',
			'Admin users who need access to ACF',
			array( $this, 'ssm_core_acf_admin_users' ),
			'ssm_core',
			'ssm-core-acf-options',
			[
				'admins' => get_users( array('role' => 'administrator') )
			]
		);
	}
	
	
	public function ssm_core_agency_options() {

	}
	
	public function ssm_core_agency_name() {
		$agencyName = $this->ssm_get_option('ssm_core_agency_name') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_agency_name') ) : 'Secret Stache Media';
		echo '<input type="text" name="ssm_core_agency_name" value="' . $agencyName . '" class="regular-text"/>';
	}
	
	public function ssm_core_agency_url() {
		$agencyURL = $this->ssm_get_option('ssm_core_agency_url') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_agency_url') ) : 'http://secretstache.com';
		echo '<input type="text" name="ssm_core_agency_url" value="' . $agencyURL . '" class="regular-text url"/>';
		echo '<p class="description">Include <code>http(s)://</code></p>';
	}
	
	public function ssm_core_login_logo() {
		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		$loginLogo = $this->ssm_get_option('ssm_core_login_logo') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo') ) : $defaultLogo;
		$width = $this->ssm_get_option('ssm_core_login_logo_width') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo_width') ) : '230px';
		$height = $this->ssm_get_option('ssm_core_login_logo_height') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo_height') ) : 'auto';
	
		echo '<div class="login-logo-wrap">';
		echo '<img src="' . $loginLogo . '" id="logo-preview" class="login-logo" alt="Login Logo" style="height: ' . $height . '; width: ' . $width . '; "/>';
		echo '<div class="media-buttons">';
		echo '<input type="button" id="upload-image-button" class="button button-secondary" value="Upload Logo" />';
		echo '<input type="button" id="remove-image-button" class="button button-secondary" value="Remove Logo" />';
		echo '</div>';
		echo '<input type="hidden" id="ssm-core-login-logo" name="ssm_core_login_logo" value="' . $loginLogo . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-width" name="ssm_core_login_logo_width" value="' . $width . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-height" name="ssm_core_login_logo_height" value="' . $height . '">';
		echo '</div>';
	}
	
	public function ssm_acf_options() {
	
	}
	
	public function ssm_core_acf_admin_users( $args ) {
		$admins = $args['admins'];
		$acfAdmins = $this->ssm_get_option('ssm_core_acf_admin_users') != NULL ? $this->ssm_get_option('ssm_core_acf_admin_users') : array();
	
		?>
		<select id="ssm-core-acf-admin-users" name="ssm_core_acf_admin_users[]" multiple style="min-width: 200px;">
			<?php foreach ( $admins as $admin ) { ?>
				<?php $selected = in_array( $admin->ID, $acfAdmins ) ? ' selected' : ''; ?>
				<option value="<?php echo $admin->ID; ?>"<?php echo $selected; ?>>
					<?php echo $admin->user_login; ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	public function add_ssm_options_page() {

		if ( ! current_theme_supports('ssm-admin-branding') && ! current_theme_supports('ssm-admin-branding') )
		  return;
	
		add_submenu_page(
		'options-general.php',
		  'SSM Core', // page title
		  'Core', // menu title
		'manage_options',
		'ssm_core',
		array( $this, 'ssm_core_options_page' )
	  );
	
	}
	
	public function ssm_core_options_page() {
		require_once( SSMC_DIR . 'admin/templates/admin-options.php' );
	}

	/**
	 * Replaces the login screen's WordPress logo with the 'login-logo.png' in your child theme images folder.
	 * Disabled by default. Make sure you have a login logo before using this function!
	 * Updated 2.0.1: Assumes SVG logo by default
	 * @since 1.0.0
	 */
	public function login_logo() {

		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		
		$background_image =  $this->ssm_get_option('ssm_core_login_logo') != NULL ? $this->ssm_get_option('ssm_core_login_logo') : $defaultLogo;
		$height =  $this->ssm_get_option('ssm_core_login_logo_height') != NULL ? $this->ssm_get_option('ssm_core_login_logo_height') : '128px';
		$width =  $this->ssm_get_option('ssm_core_login_logo_width') != NULL ? $this->ssm_get_option('ssm_core_login_logo_width') : '150px';
		
			?>
			<style type="text/css">
				body.login div#login h1 a {
					background-image: url(<?php echo $background_image; ?>) !important;
					background-repeat: no-repeat;
					background-size: cover;
					height: <?php echo $height; ?>;
					margin-bottom: 15px;
					width: <?php echo $width; ?>;
				}
			</style>
			<?php
		}
	
	public function load_admin_scripts( $hook ) {

		if ( $hook != 'settings_page_ssm_core' )
			return;
	
		wp_register_style( 'ssm-core-admin-css', SSMC_ADMIN_URL . 'css/admin.css', array(), SSMC_VERSION , 'all' );
		wp_enqueue_style( 'ssm-core-admin-css' );
	
		wp_enqueue_media();
	
		wp_register_script( 'ssm-core-admin-js', SSMC_ADMIN_URL . 'js/admin.js', array('jquery'), SSMC_VERSION, true );
	
		$login_logo_array = array(
			'url' => SSMC_ADMIN_URL . 'images/login-logo.png',
		);
	
		wp_localize_script( 'ssm-core-admin-js', 'login_logo', $login_logo_array );
	
		wp_enqueue_script( 'ssm-core-admin-js' );
	
	}

	// Get prb option value
	public function ssm_get_option( $option_name, $default = '' ) {

		if ( \get_option('ssm_core_options')[$option_name] != NULL ) {
			return \get_option('ssm_core_options')[$option_name];
		} else {
			return $default;
		}
	}

	public function ssm_save_json() {
		return $this->plugin_root_dir . '/acf';
	}

	public function ssm_save_folder_json( $folders ) {
		$folders['Field Factory'] = $this->plugin_root_dir . '/acf';
		return $folders;
	}

	public function ssm_load_json( $paths ) {
		$paths[] = $this->plugin_root_dir . '/acf';
		return $paths;
	}



}
