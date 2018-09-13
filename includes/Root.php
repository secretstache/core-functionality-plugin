<?php

namespace SSM\Includes;

use SSM\Includes\Loader;
use SSM\Includes\I18n;
use SSM\Includes\Helpers as SSMH;
use SSM\Admin\Admin;
use SSM\Admin\AdminSetup;
use SSM\Front\Front;
use SSM\Front\FrontSetup;

class Root {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SSM_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * the front-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ssm';
		$this->loader = new Loader();

		$this->set_locale();

		$this->set_admin_modules();
		$this->set_front_modules();

		$this->set_initial_options();
		$this->define_hooks();
		$this->set_options_page();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SSM_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Fulfill arrays of admin modules and functions with initial data
	 *
	 * @since   1.0.0
     * @access  private
	 */
	private function set_admin_modules() {

		$this->admin_modules = array(
			[ "slug" => 'ssm-admin-setup', "name" => "Admin Setup" ]
		);

		$this->admin_modules_functions['ssm-admin-setup'] = array(
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
				[ "type" => "filter" , "name" => "wpseo_metabox_prio", "class" => "plugin_admin_setup", "function" => "yoast_seo_metabox_priority" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "remove_post_type_support" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "remove_dashboard_meta" ],
				[ "type" => "action" , "name" => "admin_menu", "class" => "plugin_admin_setup", "function" => "ssm_admin_menu" ],
				[ "type" => "action" , "name" => "init", "class" => "plugin_admin_setup", "function" => "move_cpts_to_admin_menu", "priority" => 25 ],
				[ "type" => "filter" , "name" => "admin_body_class", "class" => "plugin_admin_setup", "function" => "is_front_admin_body_class", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "wp_ajax_get_width_values", "class" => "plugin_admin_setup", "function" => "get_width_values" ],
				[ "type" => "action" , "name" => "wp_ajax_nopriv_get_width_values", "class" => "plugin_admin_setup", "function" => "get_width_values" ],
				[ "type" => "action" , "name" => "save_post", "class" => "plugin_admin_setup", "function" => "update_width_post_meta", "priority" => 10, "arguments" => 3 ],
				[ "type" => "filter" , "name" => "login_headerurl", "class" => "plugin_admin_setup", "function" => "login_headerurl" ],
				[ "type" => "filter" , "name" => "login_headertitle", "class" => "plugin_admin_setup", "function" => "login_headertitle" ],
				[ "type" => "filter" , "name" => "login_enqueue_scripts", "class" => "plugin_admin_setup", "function" => "login_logo" ],
				[ "type" => "filter" , "name" => "wp_mail_from_name", "class" => "plugin_admin_setup", "function" => "mail_from_name" ],
				[ "type" => "filter" , "name" => "wp_mail_from", "class" => "plugin_admin_setup", "function" => "wp_mail_from" ],
				[ "type" => "action" , "name" => "wp_before_admin_bar_render", "class" => "plugin_admin_setup", "function" => "remove_icon_bar" ],
				[ "type" => "filter" , "name" => "admin_footer_text", "class" => "plugin_admin_setup", "function" => "admin_footer_text" ]
			)
		);

	}

	/**
	 * Fulfill arrays of front modules ann functions with initial data
	 *
	 * @since   1.0.0
     * @access  private
	 */
	private function set_front_modules() {

		$this->front_modules = array(
			[ "slug" => 'ssm-front-setup', "name" => "Front Setup" ]
		);

		$this->front_modules_functions['ssm-front-setup'] = array(
			"module_name" => "Front Setup",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_front_setup", "function" => "add_year_shortcode" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "set_favicon" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "ssm_do_facebook_pixel", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "ssm_setup_google_tag_manager", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "ssm_setup_google_site_verification", "priority" => 1 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "ssm_custom_head_scripts", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_footer", "class" => "plugin_front_setup", "function" => "ssm_custom_footer_scripts", "priority" => 99 ],
				[ "type" => "filter" , "name" => "gform_init_scripts_open", "class" => "plugin_front_setup", "function" => "footer_scripts_init" ],
				[ "type" => "filter" , "name" => "gfrom_cdata_open", "class" => "plugin_front_setup", "function" => "wrap_gform_cdata_open", "priority" => 10 ],
				[ "type" => "filter" , "name" => "gform_cdata_close", "class" => "plugin_front_setup", "function" => "wrap_gform_cdata_close", "priority" => 99 ]
			)
		);

	}

	/**
	 * Set up initial state of the main options (enable all of the modules and features).
	 *
	 * @since   1.0.0
     * @access  public
	 */
    public function set_initial_options() {

        //Set initial state of Admin variables
        if ( !get_option( 'admin_enabled_modules' ) ) {
            add_option( 'admin_enabled_modules' );
            update_option('admin_enabled_modules', $this->admin_modules, true);
        }

        if ( !get_option( 'admin_enabled_functions' ) ) {
            add_option( 'admin_enabled_functions' );
            update_option('admin_enabled_functions', $this->admin_modules_functions, true);
        }

        //Set initial state of Front variables
        if ( !get_option( 'front_enabled_modules' ) ) {
            add_option( 'front_enabled_modules' );
            update_option('front_enabled_modules', $this->front_modules, true);
        }

        if ( !get_option( 'front_enabled_functions' ) ) {
            add_option( 'front_enabled_functions' );
            update_option('front_enabled_functions', $this->front_modules_functions, true);
		}

	}

	/**
	 * Register all of the hooks related to the front-facing and
	 * admi functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_front_modules_functions() );
		$plugin_front = new Front( $this->get_plugin_name(), $this->get_version(), $this->get_front_modules(), $this->get_front_modules_functions() );
		$plugin_front_setup = new FrontSetup( $this->get_plugin_name(), $this->get_version(), $this->get_front_modules(), $this->get_front_modules_functions() );
		$plugin_admin_setup = new AdminSetup( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_front, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_front, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'call_registration' );

		$this->register_modules( 'front', array(
				"plugin_front_setup" => $plugin_front_setup
			)
		);

		$this->register_modules( 'admin', array(
				'plugin_admin_setup' => $plugin_admin_setup
			)
		);

	}

	/**
	 * Receive context (front,admin) and array of modules,
	 * go through it and register corresponding hooks
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_modules( $context, $objects ) {

		foreach ( $objects as $slug => $object ) {
			$$slug = $object;
		}

		${$context . "_enabled_modules"} = get_option( "{$context}_enabled_modules" );
		${$context . "_enabled_functions"}= get_option( "{$context}_enabled_functions" );

		foreach ( ${$context . "_enabled_modules"} as $module ) {
			add_theme_support( $module['slug'] );
		}

		foreach ( ${$context . "_enabled_functions"} as $slug => $function ) {
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
	 * Set up Options Page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_options_page() {

		$plugin_options = new Options( $this->get_front_modules(), $this->get_front_modules_functions(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
			
		$this->loader->add_action( 'admin_init', $plugin_options, 'ssm_core_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_options, 'add_ssm_options_page', 99 );
		$this->loader->add_action( 'admin_init', $plugin_options, 'handle_options_update' );

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
	 * @return    SSM_Loader    Orchestrates the hooks of the plugin.
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
	 * Return array of current registered admin modules
	 *
	 * @since    1.0.0
	 */
	protected function get_admin_modules() {
		return $this->admin_modules;
	}

	/**
	 * Return array of current registered admin modules functions
	 *
	 * @since    1.0.0
	 */
	protected function get_admin_modules_functions() {
		return $this->admin_modules_functions;
	}

	/**
	 * Return array of current registered public modules
	 *
	 * @since    1.0.0
	 */
	protected function get_front_modules() {
		return $this->front_modules;
	}

	/**
	 * Return array of current registered public modules functions
	 *
	 * @since    1.0.0
	 */
	protected function get_front_modules_functions() {
		return $this->front_modules_functions;
	}

}