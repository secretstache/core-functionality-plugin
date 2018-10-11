<?php

namespace SSM\Includes;

use SSM\Includes\Loader;
use SSM\Includes\I18n;
use SSM\Includes\Helpers as SSMH;
use SSM\Admin\Admin;
use SSM\Admin\AdminSetup;
use SSM\Admin\RequiredPlugins;
use SSM\Admin\FieldFactory;
use SSM\Admin\CPT;
use SSM\Front\Front;
use SSM\Front\FrontSetup;

class Root
{
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $pluginName;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->pluginName = 'ssm';
		$this->loader = new Loader();

		$this->setLocale();

		$this->setAdminModules();
		$this->setFrontModules();

		// $this->setInitialOptions();
		$this->defineHooks();
		$this->setOptionsPage();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain
	 */
	private function setLocale()
	{
		
		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'loadPluginTextdomain' );

	}

	/**
	 * Fulfill arrays of admin modules and functions with initial data
	 *
	 */
	private function setAdminModules()
	{

		$this->adminModules = array(
			[ "slug" => 'ssm-admin-setup', "name" => "Admin Setup" ],
			[ "slug" => 'ssm-required-plugins', "name" => "Required Plugins" ],
			[ "slug" => 'ssm-field-factory', "name" => "Field Factory" ],
			[ "slug" => 'ssm-cpt', "name" => "CPT" ]
		);

		$this->adminModuleFunctions['ssm-admin-setup'] = array(
			"module_name" => "Admin Setup",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_admin_setup", "function" => "removeRoles" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "removeImageLink", "priority" => 10 ],
				[ "type" => "filter" , "name" => "tiny_mce_before_init", "class" => "plugin_admin_setup", "function" => "showKitchenSink", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "widgets_init", "class" => "plugin_admin_setup", "function" => "removeWidgets" ],
				[ "type" => "action" , "name" => "wp_dashboard_setup", "class" => "plugin_admin_setup", "function" => "hostingDashboardWidget" ],
				[ "type" => "filter" , "name" => "tiny_mce_before_init", "class" => "plugin_admin_setup", "function" => "updateTinyMCE", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "the_content", "class" => "plugin_admin_setup", "function" => "removePtagsOnImages", "priority" => 10, "arguments" => 1 ],
				[ "type" => "filter" , "name" => "gallery_style", "class" => "plugin_admin_setup", "function" => "removeGalleryStyles", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "forceHomepage" ],
				[ "type" => "action" , "name" => "admin_bar_menu", "class" => "plugin_admin_setup", "function" => "removeWPNodes", "priority" => 999 ],
				[ "type" => "filter" , "name" => "wpseo_metabox_prio", "class" => "plugin_admin_setup", "function" => "yoastSeoMetaboxPriority" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "removePostTypeSupport" ],
				[ "type" => "action" , "name" => "admin_init", "class" => "plugin_admin_setup", "function" => "removeDashboardMeta" ],
				[ "type" => "action" , "name" => "admin_menu", "class" => "plugin_admin_setup", "function" => "createAdminMenu" ],
				[ "type" => "action" , "name" => "init", "class" => "plugin_admin_setup", "function" => "moveCptsToAdminMenu", "priority" => 25 ],
				[ "type" => "filter" , "name" => "admin_body_class", "class" => "plugin_admin_setup", "function" => "isFrontAdminBodyClass", "priority" => 10, "arguments" => 1 ],
				[ "type" => "action" , "name" => "wp_ajax_get_width_values", "class" => "plugin_admin_setup", "function" => "getWidthValues" ],
				[ "type" => "action" , "name" => "wp_ajax_nopriv_get_width_values", "class" => "plugin_admin_setup", "function" => "getWidthValues" ],
				[ "type" => "action" , "name" => "save_post", "class" => "plugin_admin_setup", "function" => "updateWidthPostMeta", "priority" => 10, "arguments" => 3 ],
				[ "type" => "filter" , "name" => "login_headerurl", "class" => "plugin_admin_setup", "function" => "loginHeaderurl" ],
				[ "type" => "filter" , "name" => "login_headertitle", "class" => "plugin_admin_setup", "function" => "loginHeadertitle" ],
				[ "type" => "filter" , "name" => "login_enqueue_scripts", "class" => "plugin_admin_setup", "function" => "loginLogo" ],
				[ "type" => "filter" , "name" => "wp_mail_from_name", "class" => "plugin_admin_setup", "function" => "mailFromName" ],
				[ "type" => "filter" , "name" => "wp_mail_from", "class" => "plugin_admin_setup", "function" => "wpMailFrom" ],
				[ "type" => "action" , "name" => "wp_before_admin_bar_render", "class" => "plugin_admin_setup", "function" => "removeIconBar" ],
				[ "type" => "filter" , "name" => "admin_footer_text", "class" => "plugin_admin_setup", "function" => "adminFooterText" ]
			)
		);

		$this->adminModuleFunctions['ssm-required-plugins'] = array(
			"module_name" => "Required Plugins",
			"hooks" => array(
				[ "type" => "filter" , "name" => "sober/bundle/file", "class" => "plugin_required_plugins", "function" => "checkRequiredPlugins" ]
			)
		);

		$this->adminModuleFunctions['ssm-field-factory'] = array(
			"module_name" => "Field Factory",
			"hooks" => array(
				[ "type" => "filter" , "name" => "acf/settings/save_json", "class" => "plugin_field_factory", "function" => "saveJSON" ],
				[ "type" => "filter" , "name" => "acf/settings/load_json", "class" => "plugin_field_factory", "function" => "loadJSON", "priority" => 10, "arguments" => 1 ]
			)
		);

		$this->adminModuleFunctions['ssm-cpt'] = array(
			"module_name" => "CPT",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_cpt", "function" => "registerPostTypes" ],
				[ "type" => "action" , "name" => "init", "class" => "plugin_cpt", "function" => "registerTaxonomies" ],
				[ "type" => "action" , "name" => "init", "class" => "plugin_cpt", "function" => "registerTerms" ]
			)
		);

	}

	/**
	 * Fulfill arrays of front modules ann functions with initial data
	 *
	 */
	private function setFrontModules()
	{

		$this->frontModules = array(
			[ "slug" => 'ssm-front-setup', "name" => "Front Setup" ]
		);

		$this->frontModuleFunctions['ssm-front-setup'] = array(
			"module_name" => "Front Setup",
			"hooks" => array(
				[ "type" => "action" , "name" => "init", "class" => "plugin_front_setup", "function" => "addYearShortcode" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "setFavicon" ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "doFacebookPixel", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "setupGoogleTagManager", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "setupGoogleSiteVerification", "priority" => 1 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "customHeadScripts", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_footer", "class" => "plugin_front_setup", "function" => "customFooterScripts", "priority" => 99 ],
				[ "type" => "filter" , "name" => "gform_init_scripts_open", "class" => "plugin_front_setup", "function" => "footerScriptsInit" ],
				[ "type" => "filter" , "name" => "gfrom_cdata_open", "class" => "plugin_front_setup", "function" => "wrapGformCdataOpen", "priority" => 10 ],
				[ "type" => "action" , "name" => "wp_head", "class" => "plugin_front_setup", "function" => "injectInlineCss", "priority" => 99 ],
				[ "type" => "action" , "name" => "wp_footer", "class" => "plugin_front_setup", "function" => "injectInlineJs", "priority" => 99 ],
				[ "type" => "action" , "name" => "admin_notices", "class" => "plugin_front_setup", "function" => "saveReminderNotice" ]
			)
		);

	}

	/**
	 * Set up initial state of the main options (enable all of the modules and features).
	 *
	 */
	// public function setInitialOptions()
	// {

    //     //Set initial state of Admin variables
    //     if ( !get_option( 'admin_enabled_modules' ) ) {
    //         add_option( 'admin_enabled_modules' );
    //         update_option('admin_enabled_modules', $this->adminModules, true);
    //     }

    //     if ( !get_option( 'admin_enabled_functions' ) ) {
    //         add_option( 'admin_enabled_functions' );
    //         update_option('admin_enabled_functions', $this->adminModuleFunctions, true);
    //     }

    //     //Set initial state of Front variables
    //     if ( !get_option( 'front_enabled_modules' ) ) {
    //         add_option( 'front_enabled_modules' );
    //         update_option('front_enabled_modules', $this->frontModules, true);
    //     }

    //     if ( !get_option( 'front_enabled_functions' ) ) {
    //         add_option( 'front_enabled_functions' );
    //         update_option('front_enabled_functions', $this->frontModuleFunctions, true);
	// 	}

	// }

	/**
	 * Register all of the hooks related to the front-facing and
	 * admi functionality of the plugin.
	 */
	private function defineHooks()
	{

		$plugin_admin = new Admin( $this->getPluginName(), $this->getVersion(), $this->getAdminModules(), $this->getFrontModuleFunctions() );
		$plugin_front = new Front( $this->getPluginName(), $this->getVersion(), $this->getFrontModules(), $this->getFrontModuleFunctions() );
		$plugin_front_setup = new FrontSetup( $this->getPluginName(), $this->getVersion(), $this->getFrontModules(), $this->getFrontModuleFunctions() );
		$plugin_admin_setup = new AdminSetup( $this->getPluginName(), $this->getVersion(), $this->getAdminModules(), $this->getAdminModuleFunctions() );
		$plugin_required_plugins = new RequiredPlugins( $this->getPluginName(), $this->getVersion(), $this->getAdminModules(), $this->getAdminModuleFunctions() );
		$plugin_field_factory = new FieldFactory( $this->getPluginName(), $this->getVersion(), $this->getAdminModules(), $this->getAdminModuleFunctions() );
		$plugin_cpt = new CPT( $this->getPluginName(), $this->getVersion(), $this->getAdminModules(), $this->getAdminModuleFunctions() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueueStyles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueueScripts' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_front, 'enqueueStyles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_front, 'enqueueScripts' );

		$this->registerModules( 'front', array(
				"plugin_front_setup" => $plugin_front_setup
			)
		);

		$this->registerModules( 'admin', array(
				'plugin_admin_setup' => $plugin_admin_setup,
				'plugin_required_plugins' => $plugin_required_plugins,
				'plugin_field_factory' => $plugin_field_factory,
				'plugin_cpt' => $plugin_cpt
			)
		);

	}

	/**
	 * Receive context (front,admin) and array of modules,
	 * go through it and register corresponding hooks
	 */
	private function registerModules( $context, $objects )
	{

		foreach ( $objects as $slug => $object ) {
			$$slug = $object;
		}

		// ${$context . "_enabled_modules"} = get_option( "{$context}_enabled_modules" );
		// ${$context . "_enabled_functions"}= get_option( "{$context}_enabled_functions" );

		// foreach ( ${$context . "_enabled_modules"} as $module ) {
		// 	add_theme_support( $module['slug'] );
		// }

		// foreach ( ${$context . "_enabled_functions"} as $slug => $function ) {
		foreach ( $this->{$context . "ModuleFunctions"} as $slug => $function ) {
			// if ( current_theme_supports( $slug ) ) {
				foreach ( $function['hooks'] as $hook ) {

					$priority = ( isset( $hook['priority'] ) && $hook['priority'] != '' ) ? $hook['priority'] : '';
					$arguments = ( isset( $hook['arguments'] ) && $hook['arguments'] != '' ) ? $hook['arguments'] : '';

					call_user_func_array(
						array( $this->loader, "add_{$hook['type']}" ),
						array( $hook['name'], ${$hook['class']}, $hook['function'], $priority, $arguments )
					);
					
				}
			// }
		}

	}

	/**
	 * Set up Options Page
	 */
	private function setOptionsPage()
	{

		$plugin_options = new Options( $this->getFrontModules(), $this->getFrontModuleFunctions(), $this->getAdminModules(), $this->getAdminModuleFunctions() );
			
		$this->loader->add_action( 'admin_init', $plugin_options, 'ssmCoreSettings' );
		$this->loader->add_action( 'admin_menu', $plugin_options, 'addSsmOptionsPage', 99 );
		$this->loader->add_action( 'admin_init', $plugin_options, 'handleOptionsUpdate' );

	}

	/**
	 * Run the loader to execute all of the registered hooks
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * Return plugin's name
	 */
	public function getPluginName()
	{
		return $this->pluginName;
	}

	/**
	 * Return plugin's loader
	 */
	public function getLoader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Return array of current registered admin modules
	 */
	protected function getAdminModules()
	{
		return $this->adminModules;
	}

	/**
	 * Return array of current registered admin module functions
	 *
	 * @since    1.0.0
	 */
	protected function getAdminModuleFunctions()
	{
		return $this->adminModuleFunctions;
	}

	/**
	 * Return array of current registered public modules
	 *
	 * @since    1.0.0
	 */
	protected function getFrontModules()
	{
		return $this->frontModules;
	}

	/**
	 * Return array of current registered public module functions
	 *
	 * @since    1.0.0
	 */
	protected function getFrontModuleFunctions() {
		return $this->frontModuleFunctions;
	}

}