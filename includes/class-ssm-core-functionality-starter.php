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
 * @package    SSM_Core_Functionality_Starter
 * @subpackage SSM_Core_Functionality_Starter/includes
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
 * @package    SSM_Core_Functionality_Starter
 * @subpackage SSM_Core_Functionality_Starter/includes
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class SSM_Core_Functionality_Starter {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SSM_Core_Functionality_Starter_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * The plugin's root dir
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The plugin's root dir
	 */
	protected $plugin_root_dir;

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
		$this->plugin_name = 'ssm-core-functionality-starter';

		$this->plugin_root_dir = WP_PLUGIN_DIR . "/" . get_plugin_data( plugin_dir_path(__FILE__) )['TextDomain'];

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
	 * - SSM_Core_Functionality_Starter_Loader. Orchestrates the hooks of the plugin.
	 * - SSM_Core_Functionality_Starter_i18n. Defines internationalization functionality.
	 * - SSM_Core_Functionality_Starter_Admin. Defines all hooks for the admin area.
	 * - SSM_Core_Functionality_Starter_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-core-functionality-starter-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-core-functionality-starter-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-core-functionality-starter-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ssm-core-functionality-starter-public.php';

		$this->loader = new SSM_Core_Functionality_Starter_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SSM_Core_Functionality_Starter_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SSM_Core_Functionality_Starter_i18n();

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

		$plugin_admin = new SSM_Core_Functionality_Starter_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_root_dir() );

		/* Testing purposes */

		add_theme_support( 'term_adding_prevent' );
		add_theme_support( 'term_removing_prevent' );
		add_theme_support( 'set_default_terms' );
		add_theme_support( 'ssm-required-plugins' );
		add_theme_support( 'ssm-field-factory' );

		add_theme_support( 'ssm-acf' );
 		add_theme_support( 'ssm-admin-branding' );
  		add_theme_support( 'ssm-admin-setup' );
		add_theme_support( 'ssm-dashboard-widgets' );
		add_theme_support( 'ssm-options' );
		
		/* Inhereted from ssm-core */

		if ( current_theme_supports( 'ssm-acf' ) ) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'remove_acf_menu');
		}

		if ( current_theme_supports( 'ssm-admin-branding' ) ) {
			$this->loader->add_filter( 'login_headerurl', $plugin_admin, 'login_headerurl' );
			$this->loader->add_filter( 'login_headertitle', $plugin_admin, 'login_headertitle' );
			$this->loader->add_filter( 'wp_mail_from_name', $plugin_admin, 'mail_from_name' );
			$this->loader->add_filter( 'wp_mail_from', $plugin_admin, 'wp_mail_from' );
			$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'remove_wp_icon_from_admin_bar' );
			$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'admin_footer_text' );
		}

		if ( current_theme_supports( 'ssm-admin-setup' ) ) {
			$this->loader->add_action( 'init', $plugin_admin, 'remove_roles');  
			$this->loader->add_action( 'admin_init', $plugin_admin, 'remove_image_link', 10);  
			$this->loader->add_filter( 'tiny_mce_before_init', $plugin_admin, 'show_kitchen_sink', 10, 1 );
			$this->loader->add_action( 'widgets_init', $plugin_admin, 'remove_widgets');
			$this->loader->add_filter( 'tiny_mce_before_init', $plugin_admin, 'update_tiny_mce', 10, 1 );
			$this->loader->add_filter( 'the_content', $plugin_admin, 'remove_ptags_on_images', 10, 1 );
			$this->loader->add_filter( 'gallery_style', $plugin_admin, 'remove_gallery_styles', 10, 1 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'force_homepage');
			$this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'remove_wp_nodes', 999 );
			$this->loader->add_filter( 'wpseo_metabox_prio', $plugin_admin, 'yoast_seo_metabox_priority' );
		}

		if ( current_theme_supports( 'ssm-dashboard-widgets' ) ) {
			$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'hosting_dashboard_widget' );
		}

		if ( current_theme_supports( 'ssm-options' ) ) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'ssm_core_settings' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_ssm_options_page', 99 );
			$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin, 'login_logo' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'load_admin_scripts', 10, 1 );
		}

		if ( current_theme_supports( 'ssm-field-factory' ) ) {
			$this->loader->add_filter( 'acf/settings/save_json', $plugin_admin, 'ssm_save_json' );
			$this->loader->add_filter( 'aljm_save_json', $plugin_admin, 'ssm_save_folder_json', 10, 1 );
			$this->loader->add_filter( 'acf/settings/load_json', $plugin_admin, 'ssm_load_json', 10, 1 );
		}

		/**
		 *	10, 20, 30 - priorities. (firstly we register post types, then - taxonomies, then - terms)
		 *	1 - number of arguments to be passed to function (1 array of args in this case for all functions)
		 */

		/* Registrations */
		
		$this->loader->add_action( 'init', $plugin_admin, 'call_registration' );

		$this->loader->add_action( 'custom_cpt_hook', $plugin_admin, 'register_post_types', 10, 1 ); 
		$this->loader->add_action( 'custom_taxonomies_hook', $plugin_admin, 'register_taxonomies', 20, 1 );
		$this->loader->add_action( 'custom_terms_hook', $plugin_admin, 'register_terms', 30, 1 );

		/* Additional features */

		if ( current_theme_supports( 'ssm-required-plugins' ) ) {
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'check_for_required_plugins' );
			$this->loader->add_action( 'custom_required_plugins_hook', $plugin_admin, 'check_required_plugins', 10, 1 );	
		}
		
		if ( current_theme_supports( 'term_adding_prevent' ) ) {
			$this->loader->add_action( 'pre_insert_term', $plugin_admin, 'term_adding_prevent', 10, 2 );
		}

		if ( current_theme_supports( 'term_removing_prevent' ) ) {
			$this->loader->add_action( 'delete_term_taxonomy', $plugin_admin, 'term_removing_prevent', 10, 1 );
		}

		if ( current_theme_supports( 'set_default_terms' ) ) {
			$this->loader->add_action( 'save_post', $plugin_admin, 'set_default_terms', 30, 2 );
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SSM_Core_Functionality_Starter_Public( $this->get_plugin_name(), $this->get_version() );

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
	 * @return    SSM_Core_Functionality_Starter_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Retrieve the plugin's root path.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_plugin_root_dir() {
		return $this->plugin_root_dir;
	}

}