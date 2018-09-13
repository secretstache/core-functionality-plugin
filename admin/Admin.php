<?php

namespace SSM\Admin;

class Admin {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * The list of admin modules to be included in the core
	 */
    protected $admin_modules;

    /**
	 * The array of arguments in accordance with corresponding admin core modules
	 */
    protected $admin_modules_functions;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version, $admin_modules, $admin_modules_functions ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->admin_modules = $admin_modules;
        $this->admin_modules_functions = $admin_modules_functions;

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ssm-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ssm-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Step by step, it adds items to main multidemensional array of post types,
	 * taxonomies and terms and call corresponding hooks using do_action().
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

		array_push( $tax_args, array(
			"tax_name" 		=> "acf_category",
			"cpt_name" 		=> "acf-field-group",
			"slug" 			=> "category",
			"text_domain"	=> "category",
			"single" 		=> "Category",
			"plural" 		=> "Category",
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

		array_push( $terms_args, array(
			"term_name" 		=> "Modules",
			"taxonomy_name" 	=> "acf_category",
			"slug"				=> "modules",
		) );

		array_push( $terms_args, array(
			"term_name" 		=> "Lists",
			"taxonomy_name" 	=> "acf_category",
			"slug"				=> "lists",
		) );

		array_push( $terms_args, array(
			"term_name" 		=> "Components",
			"taxonomy_name" 	=> "acf_category",
			"slug"				=> "components",
		) );

		array_push( $terms_args, array(
			"term_name" 		=> "Options",
			"taxonomy_name" 	=> "acf_category",
			"slug"				=> "options",
		) );
		
		// new terms go here...

		if ( !empty( $terms_args ) ) {
			do_action( 'custom_terms_hook', $terms_args );
		}

	}

}
