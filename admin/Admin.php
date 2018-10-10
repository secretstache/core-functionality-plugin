<?php

namespace SSM\Admin;

class Admin
{

	/**
	 * The ID of this plugin.
	 */
	private $pluginName;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * The list of admin modules to be included in the core
	 */
    protected $adminModules;

    /**
	 * The array of arguments in accordance with corresponding admin core modules
	 */
    protected $adminModuleFunctions;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $pluginName, $version, $adminModules, $adminModuleFunctions )
	{

		$this->pluginName = $pluginName;
		$this->version = $version;

		$this->adminModules = $adminModules;
        $this->adminModuleFunctions = $adminModuleFunctions;

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style( $this->pluginName, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueueScripts()
	{		
		wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	}

}
