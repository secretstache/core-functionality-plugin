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

		$modules = array(
			[ "slug" => 'ssm-cpt', "name" => "CPT" ],
			[ "slug" => 'ssm-taxonomies', "name" => "Taxonomies" ],
			[ "slug" => 'ssm-required-plugins', "name" => "Required Plugins" ],
			[ "slug" => 'ssm-admin-branding', "name" => "Admin Branding" ],
			[ "slug" => 'ssm-admin-setup', "name" => "Admin Setup" ],
			[ "slug" => 'ssm-field-factory', "name" => "Field Factory" ]
		);

		$modules_functions['ssm-cpt'] = array(
			"module_name" => "CPT",
			"hooks" => array(
				[ "type" => "action" , "name" => "custom_cpt_hook", "class" => "plugin_cpt", "function" => "register_post_types", "priority" => 10, "arguments" => 1 ]
			)
		);

		$modules_functions['ssm-taxonomies'] = array(
			"module_name" => "Taxonomies",
			"hooks" => array(
				[ "type" => "action" , "name" => "custom_taxonomies_hook", "class" => "plugin_taxonomies", "function" => "register_taxonomies", "priority" => 20, "arguments" => 1 ],
				[ "type" => "action" , "name" => "custom_terms_hook", "class" => "plugin_taxonomies", "function" => "register_terms", "priority" => 30, "arguments" => 1 ],
				[ "type" => "action" , "name" => "pre_insert_term", "class" => "plugin_taxonomies", "function" => "term_adding_prevent", "priority" => 10, "arguments" => 2 ],
				[ "type" => "action" , "name" => "delete_terms_taxonomy", "class" => "plugin_taxonomies", "function" => "term_removing_prevent", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "save_post", "class" => "plugin_taxonomies", "function" => "set_default_terms", "priority" => 30, "arguments" => 2 ]
			)
		);

		$modules_functions['ssm-required-plugins'] = array(
			"module_name" => "Required Plugins",
			"hooks" => array(
				[ "type" => "action" , "name" => "admin_notices", "class" => "plugin_required_plugins", "function" => "list_of_required_plugins" ],
				[ "type" => "action" , "name" => "custom_required_plugins_hook", "class" => "plugin_required_plugins", "function" => "check_required_plugins", "priority" => 10, "arguments" => 1 ]
			)
		);

		$modules_functions['ssm-admin-branding'] = array(
			"module_name" => "Admin Branding",
			"hooks" => array(
				[ "type" => "filter" , "name" => "login_headerurl", "class" => "plugin_admin_branding", "function" => "login_headerurl" ],
				[ "type" => "filter" , "name" => "login_headertitle", "class" => "plugin_admin_branding", "function" => "login_headertitle" ],
				[ "type" => "filter" , "name" => "login_enqueue_scripts", "class" => "plugin_admin_branding", "function" => "login_logo" ],
				[ "type" => "filter" , "name" => "wp_mail_from_name", "class" => "plugin_admin_branding", "function" => "mail_from_name" ],
				[ "type" => "filter" , "name" => "wp_mail_from", "class" => "plugin_admin_branding", "function" => "wp_mail_from" ],
				[ "type" => "action" , "name" => "wp_before_admin_bar_render", "class" => "plugin_admin_branding", "function" => "remove_icon_bar" ],
				[ "type" => "filter" , "name" => "admin_footer_text", "class" => "plugin_admin_branding", "function" => "admin_footer_text" ]
			)
		);

		$modules_functions['ssm-admin-setup'] = array(
			"module_name" => "Admin Setup",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_admin_setup", "function" => "remove_roles" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "remove_image_link", "priority" => 10 ],
				[ "type" => "filter" , "name" => "tiny_mce_before_init", "class" => "plugin_admin_setup", "function" => "show_kitchen_sink", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "widgets_init", "class" => "plugin_admin_setup", "function" => "remove_widgets" ],
				[ "type" => "action" , "name" => "wp_dashboard_setup", "class" => "plugin_admin_setup", "function" => "hosting_dashboard_widget" ],
				[ "type" => "filter" , "name" => "tiny_mce_before_init", "class" => "plugin_admin_setup", "function" => "update_tiny_mce", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "the_content", "class" => "plugin_admin_setup", "function" => "remove_ptags_on_images", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "gallery_style", "class" => "plugin_admin_setup", "function" => "remove_gallery_styles", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "force_homepage" ],
				[ "type" => "action" , "name" => "admin_bar_menu", "class" => "plugin_admin_setup", "function" => "remove_wp_nodes", "priority" => 999 ],
				[ "type" => "filter" , "name" => "wpseo_metabox_prio", "class" => "plugin_admin_setup", "function" => "yoast_seo_metabox_priority" ]		
			)
		);

		$modules_functions['ssm-field-factory'] = array(
			"module_name" => "Field Factory",
			"hooks" => array(
				[ "type" => "filter" , "name" => "acf/settings/save_json", "class" => "plugin_field_factory", "function" => "ssm_save_json" ],
				[ "type" => "filter" , "name" => "aljm_save_json", "class" => "plugin_field_factory", "function" => "ssm_save_folder_json", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "acf/settings/load_json", "class" => "plugin_field_factory", "function" => "ssm_load_json", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_field_factory", "function" => "remove_acf_menu" ]
			)
		);
		
		$plugin_admin = new SSM_Core_Functionality_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_cpt = new SSM_Core_Functionality_CPT( $this->get_plugin_name(), $this->get_version() );
		$plugin_taxonomies = new SSM_Core_Functionality_Taxonomies( $this->get_plugin_name(), $this->get_version() );
		$plugin_required_plugins = new SSM_Core_Functionality_Required_Plugins( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_setup = new SSM_Core_Functionality_Admin_Setup( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_branding = new SSM_Core_Functionality_Admin_Branding( $this->get_plugin_name(), $this->get_version() );
		$plugin_field_factory = new SSM_Core_Functionality_Field_Factory( $this->get_plugin_name(), $this->get_version() );
		$plugin_options = new SSM_Core_Functionality_Options( $this->get_plugin_name(), $this->get_version(), $modules, $modules_functions );
		
		$this->loader->add_action( 'admin_init', $plugin_options, 'ssm_core_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_options, 'add_ssm_options_page', 99 );
		$this->loader->add_action( 'admin_init', $plugin_options, 'handle_options_update' );
		$this->loader->add_action( 'init', $plugin_admin, 'call_registration' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$ssm_enabled_modules = get_option( 'ssm_enabled_modules' );
		$ssm_enabled_functions = get_option( 'ssm_enabled_functions' );
			
		foreach ( $ssm_enabled_modules as $module ) {
			add_theme_support( $module['slug'] );
		}

		foreach ( $ssm_enabled_functions as $slug => $function ) {
			if ( current_theme_supports( $slug ) ) {
				foreach ( $function['hooks'] as $hook ) {
					call_user_func_array(
						array( $this->loader, "add_{$hook['type']}" ),
						array( $hook['name'], ${$hook['class']}, $hook['function'], $hook['priority'], $hook['arguments'] )
					);
					
				}
			}
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