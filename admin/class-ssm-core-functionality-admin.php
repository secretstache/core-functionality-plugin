<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    SSM_Core_Functionality
 * @subpackage SSM_Core_Functionality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SSM_Core_Functionality
 * @subpackage SSM_Core_Functionality/admin
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class SSM_Core_Functionality_Admin {

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
	 * The list of admin modules to be included in the core
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $admin_modules;

    /**
	 * The array of arguments in accordance with corresponding admin core modules
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $admin_modules_functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $admin_modules, $admin_modules_functions ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->admin_modules = $admin_modules;
        $this->admin_modules_functions = $admin_modules_functions;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ssm-core-functionality-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ssm-core-functionality-admin.js', array( 'jquery' ), $this->version, false );
	}

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

}
