<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    SSM_Core_Functionality
 * @subpackage SSM_Core_Functionality/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SSM_Core_Functionality
 * @subpackage SSM_Core_Functionality/includes
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class SSM_Core_Functionality {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SSM_Core_Functionality_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ssm-core-functionality';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SSM_Core_Functionality_Loader. Orchestrates the hooks of the plugin.
	 * - SSM_Core_Functionality_i18n. Defines internationalization functionality.
	 * - SSM_Core_Functionality_Admin. Defines all hooks for the admin area.
	 * - SSM_Core_Functionality_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-core-functionality-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-core-functionality-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ssm-core-functionality-public.php';

		/**
		 * The class responsible for CPT functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-cpt.php';

		$this->loader = new SSM_Core_Functionality_Loader();

		/**
		 * The class responsible for custom taxonomies functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-taxonomies.php';

		/**
		 * The class responsible for Required Plugins functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-required-plugins.php';

		/**
		 * The class responsible for Admin Setup functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-admin-setup.php';

		$this->loader = new SSM_Core_Functionality_Loader();

		/**
		 * The class responsible for Admin Branding functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-admin-branding.php';

		/**
		 * The class responsible for Options functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-options.php';

		/**
		 * The class responsible for Field Factory module functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-field-factory.php';


		$this->loader = new SSM_Core_Functionality_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SSM_Core_Functionality_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SSM_Core_Functionality_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SSM_Core_Functionality_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$plugin_cpt = new SSM_Core_Functionality_CPT( $this->get_plugin_name(), $this->get_version() );
		$plugin_taxonomies = new SSM_Core_Functionality_Taxonomies( $this->get_plugin_name(), $this->get_version() );
		$plugin_required_plugins = new SSM_Core_Functionality_Required_Plugins( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_setup = new SSM_Core_Functionality_Admin_Setup( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_branding = new SSM_Core_Functionality_Admin_Branding( $this->get_plugin_name(), $this->get_version() );
		$plugin_options = new SSM_Core_Functionality_Options( $this->get_plugin_name(), $this->get_version() );
		$plugin_field_factory = new SSM_Core_Functionality_Field_Factory( $this->get_plugin_name(), $this->get_version() );

		/* Enable all modules for testing purposes */

		add_theme_support( 'ssm-cpt' );
		add_theme_support( 'ssm-taxonomies' );
		add_theme_support( 'ssm-required-plugins' );
 		add_theme_support( 'ssm-admin-branding' );
  		add_theme_support( 'ssm-admin-setup' );
		add_theme_support( 'ssm-options' );
		add_theme_support( 'ssm-field-factory' );
		
		if ( current_theme_supports( 'ssm-cpt' ) ) {
			$this->loader->add_action( 'custom_cpt_hook', $plugin_cpt, 'register_post_types', 10, 1 ); 
		}

		if ( current_theme_supports( 'ssm-taxonomies' ) ) {
			$this->loader->add_action( 'custom_taxonomies_hook', $plugin_taxonomies, 'register_taxonomies', 20, 1 );
			$this->loader->add_action( 'custom_terms_hook', $plugin_taxonomies, 'register_terms', 30, 1 );
			$this->loader->add_action( 'pre_insert_term', $plugin_taxonomies, 'term_adding_prevent', 10, 2 );
			$this->loader->add_action( 'delete_term_taxonomy', $plugin_taxonomies, 'term_removing_prevent', 10, 1 );
			$this->loader->add_action( 'save_post', $plugin_taxonomies, 'set_default_terms', 30, 2 );
		}

		if ( current_theme_supports( 'ssm-required-plugins' ) ) {
			$this->loader->add_action( 'admin_notices', $plugin_required_plugins, 'list_of_required_plugins' );
			$this->loader->add_action( 'custom_required_plugins_hook', $plugin_required_plugins, 'check_required_plugins', 10, 1 );	
		}

		if ( current_theme_supports( 'ssm-admin-branding' ) ) {

			$admin_branding_arguments = array(
				[ "type" => "filter" , "hook" => "login_header_url", "function" => "login_headerurl" ],
				[ "type" => "filter" , "hook" => "login_headertitle", "function" => "login_headertitle" ],
				[ "type" => "filter" , "hook" => "wp_mail_from_name", "function" => "mail_from_name" ],
				[ "type" => "filter" , "hook" => "wp_mail_from", "function" => "wp_mail_from" ],
				[ "type" => "action" , "hook" => "wp_before_admin_bar_render", "function" => "remove_wp_icon_from_admin_bar" ],
				[ "type" => "filter" , "hook" => "admin_footer_text", "function" => "admin_footer_text" ]
			);

			$this->loader->add_filter( 'login_headerurl', $plugin_admin_branding, 'login_headerurl' );
			$this->loader->add_filter( 'login_headertitle', $plugin_admin_branding, 'login_headertitle' );
			$this->loader->add_filter( 'wp_mail_from_name', $plugin_admin_branding, 'mail_from_name' );
			$this->loader->add_filter( 'wp_mail_from', $plugin_admin_branding, 'wp_mail_from' );
			$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin_branding, 'remove_wp_icon_from_admin_bar' );
			$this->loader->add_filter( 'admin_footer_text', $plugin_admin_branding, 'admin_footer_text' );
		}

		if ( current_theme_supports( 'ssm-admin-setup' ) ) {
			$this->loader->add_action( 'init', $plugin_admin_setup, 'remove_roles');  
			$this->loader->add_action( 'admin_init', $plugin_admin_setup, 'remove_image_link', 10);  
			$this->loader->add_filter( 'tiny_mce_before_init', $plugin_admin_setup, 'show_kitchen_sink', 10, 1 );
			$this->loader->add_action( 'widgets_init', $plugin_admin_setup, 'remove_widgets');
			$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin_setup, 'hosting_dashboard_widget' );
			$this->loader->add_filter( 'tiny_mce_before_init', $plugin_admin_setup, 'update_tiny_mce', 10, 1 );
			$this->loader->add_filter( 'the_content', $plugin_admin_setup, 'remove_ptags_on_images', 10, 1 );
			$this->loader->add_filter( 'gallery_style', $plugin_admin_setup, 'remove_gallery_styles', 10, 1 );
			$this->loader->add_action( 'admin_init', $plugin_admin_setup, 'force_homepage');
			$this->loader->add_action( 'admin_bar_menu', $plugin_admin_setup, 'remove_wp_nodes', 999 );
			$this->loader->add_filter( 'wpseo_metabox_prio', $plugin_admin_setup, 'yoast_seo_metabox_priority' );
		}

		if ( current_theme_supports( 'ssm-options' ) ) {
			$this->loader->add_action( 'admin_init', $plugin_options, 'ssm_core_settings' );
			$this->loader->add_action( 'admin_menu', $plugin_options, 'add_ssm_options_page', 99 );
			$this->loader->add_action( 'login_enqueue_scripts', $plugin_options, 'login_logo' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_options, 'load_admin_scripts', 10, 1 );
		}

		if ( current_theme_supports( 'ssm-field-factory' ) ) {
			$this->loader->add_filter( 'acf/settings/save_json', $plugin_field_factory, 'ssm_save_json' );
			$this->loader->add_filter( 'aljm_save_json', $plugin_field_factory, 'ssm_save_folder_json', 10, 1 );
			$this->loader->add_filter( 'acf/settings/load_json', $plugin_field_factory, 'ssm_load_json', 10, 1 );
			$this->loader->add_action( 'admin_init', $plugin_field_factory, 'remove_acf_menu');
		}

		$this->loader->add_action( 'init', $plugin_admin, 'call_registration' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SSM_Core_Functionality_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SSM_Core_Functionality_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}