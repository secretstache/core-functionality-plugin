<?php

namespace SSM\Front;

class Front
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
	 * The list of public modules to be included in the core
	 */
    protected $publicModules;

    /**
	 * The array of arguments in accordance with corresponding public core modules
	 */
	protected $publicModuleFunctions;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $pluginName, $version, $publicModules, $publicModuleFunctions )
	{

		$this->pluginName = $pluginName;
		$this->version = $version;

		$this->publicModules = $publicModules;
        $this->publicModuleFunctions = $publicModuleFunctions;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style( $this->pluginName, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
	}

}
