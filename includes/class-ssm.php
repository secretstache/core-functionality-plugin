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
 * @package    SSM
 * @subpackage SSM/includes
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
 * @package    SSM
 * @subpackage SSM/includes
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class SSM {

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
		$this->plugin_name = 'ssm';

		$this->load_dependencies();
		$this->set_locale();

		$this->set_admin_modules();
		$this->set_public_modules();

		$this->set_initial_options();
		$this->set_helpers_alias();

		$this->define_public_hooks();
		$this->define_admin_hooks();
		$this->define_general_hooks();

		$this->set_options_page();
	}

	/**
	 * Load the required dependencies for this plugin.
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for Options page registration
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-options.php';

		/**
		 * The class responsible for Helpers registration
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-helpers.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ssm-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ssm-public.php';

		/**
		 * The class responsible for CPT functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-cpt.php';

		/**
		 * The class responsible for custom taxonomies functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-taxonomies.php';

		/**
		 * The class responsible for Required Plugins functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-required-plugins.php';

		/**
		 * The class responsible for Admin Setup functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-admin-setup.php';

		/**
		 * The class responsible for Admin Branding functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-admin-branding.php';

		/**
		 * The class responsible for Field Factory module functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ssm-field-factory.php';
	
		/**
		 * The class responsible for GForm module functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ssm-gform.php';

		/**
		 * The class responsible for Public Setup module functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ssm-public-setup.php';


		$this->loader = new SSM_Loader();
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

		$plugin_i18n = new SSM_i18n();

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
			[ "slug" => 'ssm-cpt', "name" => "CPT" ],
			[ "slug" => 'ssm-taxonomies', "name" => "Taxonomies" ],
			[ "slug" => 'ssm-required-plugins', "name" => "Required Plugins" ],
			[ "slug" => 'ssm-admin-branding', "name" => "Admin Branding" ],
			[ "slug" => 'ssm-admin-setup', "name" => "Admin Setup" ],
			[ "slug" => 'ssm-field-factory', "name" => "Field Factory" ]
		);

		$this->admin_modules_functions['ssm-cpt'] = array(
			"module_name" => "CPT",
			"hooks" => array(
				[ "type" => "action" , "name" => "custom_cpt_hook", "class" => "plugin_cpt", "function" => "register_post_types", "priority" => 10, "arguments" => 1 ]
			)
		);

		$this->admin_modules_functions['ssm-taxonomies'] = array(
			"module_name" => "Taxonomies",
			"hooks" => array(
				[ "type" => "action" , "name" => "custom_taxonomies_hook", "class" => "plugin_taxonomies", "function" => "register_taxonomies", "priority" => 20, "arguments" => 1 ],
				[ "type" => "action" , "name" => "custom_terms_hook", "class" => "plugin_taxonomies", "function" => "register_terms", "priority" => 30, "arguments" => 1 ],
				[ "type" => "action" , "name" => "pre_insert_term", "class" => "plugin_taxonomies", "function" => "term_adding_prevent", "priority" => 10, "arguments" => 2 ],
				[ "type" => "action" , "name" => "delete_terms_taxonomy", "class" => "plugin_taxonomies", "function" => "term_removing_prevent", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "save_post", "class" => "plugin_taxonomies", "function" => "set_default_terms", "priority" => 30, "arguments" => 2 ]
			)
		);

		$this->admin_modules_functions['ssm-required-plugins'] = array(
			"module_name" => "Required Plugins",
			"hooks" => array(
				[ "type" => "action" , "name" => "admin_notices", "class" => "plugin_required_plugins", "function" => "list_of_required_plugins" ],
				[ "type" => "action" , "name" => "custom_required_plugins_hook", "class" => "plugin_required_plugins", "function" => "check_required_plugins", "priority" => 10, "arguments" => 1 ]
			)
		);

		$this->admin_modules_functions['ssm-admin-branding'] = array(
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
				[ "type" => "action" , "name" => "save_post", "class" => "plugin_admin_setup", "function" => "update_width_post_meta", "priority" => 10, "arguments" => 3 ]

			)
		);

		$this->admin_modules_functions['ssm-field-factory'] = array(
			"module_name" => "Field Factory",
			"hooks" => array(
				[ "type" => "filter" , "name" => "acf/settings/save_json", "class" => "plugin_field_factory", "function" => "ssm_save_json" ],
				[ "type" => "filter" , "name" => "aljm_save_json", "class" => "plugin_field_factory", "function" => "ssm_save_folder_json", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "acf/settings/load_json", "class" => "plugin_field_factory", "function" => "ssm_load_json", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_field_factory", "function" => "remove_acf_menu" ]
			)
		);

	}

	/**
	 * Fulfill arrays of public modules ann functions with initial data
	 *
	 * @since   1.0.0
     * @access  private
	 */
	private function set_public_modules() {

		$this->public_modules = array(
			[ "slug" => 'ssm-gform', "name" => "GForm" ],
			[ "slug" => 'ssm-public-setup', "name" => "Public Setup" ]
		);

		$this->public_modules_functions['ssm-gform'] = array(
			"module_name" => "GForm",
			"hooks" => array(
				[ "type" => "filter" , "name" => "gform_init_scripts_open", "class" => "plugin_gform", "function" => "footer_scripts_init" ],
				[ "type" => "filter" , "name" => "gfrom_cdata_open", "class" => "plugin_gform", "function" => "wrap_gform_cdata_open", "priority" => 10 ],
				[ "type" => "filter" , "name" => "gform_cdata_close", "class" => "plugin_gform", "function" => "wrap_gform_cdata_close", "priority" => 99 ]
			)
		);

		$this->public_modules_functions['ssm-public-setup'] = array(
			"module_name" => "Public Setup",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_public_setup", "function" => "add_year_shortcode" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_public_setup", "function" => "set_favicon" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_public_setup", "function" => "ssm_do_facebook_pixel", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_public_setup", "function" => "ssm_setup_google_tag_manager", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_public_setup", "function" => "ssm_setup_google_site_verification", "priority" => 1 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_public_setup", "function" => "ssm_custom_head_scripts", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_footer", "class" => "plugin_public_setup", "function" => "ssm_custom_footer_scripts", "priority" => 99 ]
			)
		);

	}

	/**
	 * Set Alias for SSM_Helpers class
	 * Example of usage: echo SSM::limit_words( $text, 20 )
	 *
	 * @since   1.0.0
     * @access  private
	 */
	private function set_helpers_alias() {
		class_alias('SSM_Helpers', 'SSMH');
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

        //Set initial state of Public variables
        if ( !get_option( 'public_enabled_modules' ) ) {
            add_option( 'public_enabled_modules' );
            update_option('public_enabled_modules', $this->public_modules, true);
        }

        if ( !get_option( 'public_enabled_functions' ) ) {
            add_option( 'public_enabled_functions' );
            update_option('public_enabled_functions', $this->public_modules_functions, true);
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

		$plugin_gform = new SSM_GForm( $this->get_plugin_name(), $this->get_version(), $this->get_public_modules(), $this->get_public_modules_functions() );
		$plugin_public_setup = new SSM_Public_Setup( $this->get_plugin_name(), $this->get_version(), $this->get_public_modules(), $this->get_public_modules_functions() );

		$this->register_modules( 'public', array(
				"plugin_gform" => $plugin_gform,
				"plugin_public_setup" => $plugin_public_setup
			)
		);

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
				
		$plugin_cpt = new SSM_CPT( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		$plugin_taxonomies = new SSM_Taxonomies( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		$plugin_required_plugins = new SSM_Required_Plugins( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		$plugin_admin_setup = new SSM_Admin_Setup( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		$plugin_admin_branding = new SSM_Admin_Branding( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		$plugin_field_factory = new SSM_Field_Factory( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
		
		$this->register_modules( 'admin', array(
				'plugin_cpt' => $plugin_cpt,
				'plugin_taxonomies' => $plugin_taxonomies,
				'plugin_required_plugins' => $plugin_required_plugins,
				'plugin_admin_setup' => $plugin_admin_setup,
				'plugin_admin_branding' => $plugin_admin_branding,
				'plugin_field_factory' => $plugin_field_factory
			)
		);

	}

	/**
	 * Receive context (public,admin) and array of modules,
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
	 * Define general hooks such as: admin and public scripts enqueuing,
	 * initial CPTs, taxonomies and terms registration (call_registration)
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_general_hooks() {

		$plugin_admin = new SSM_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_admin_modules(), $this->get_public_modules_functions() );
		$plugin_public = new SSM_Public( $this->get_plugin_name(), $this->get_version(), $this->get_public_modules(), $this->get_public_modules_functions() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'call_registration' );

	}

	/**
	 * Set up Options Page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_options_page() {

		$plugin_options = new SSM_Options( $this->get_public_modules(), $this->get_public_modules_functions(), $this->get_admin_modules(), $this->get_admin_modules_functions() );
			
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
	protected function get_public_modules() {
		return $this->public_modules;
	}

	/**
	 * Return array of current registered public modules functions
	 *
	 * @since    1.0.0
	 */
	protected function get_public_modules_functions() {
		return $this->public_modules_functions;
	}

}