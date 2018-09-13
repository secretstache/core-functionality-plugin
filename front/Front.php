<?php

namespace SSM\Front;

class Front {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * The list of public modules to be included in the core
	 */
    protected $public_modules;

    /**
	 * The array of arguments in accordance with corresponding public core modules
	 */
	protected $public_modules_functions;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version, $public_modules, $public_modules_functions ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->public_modules = $public_modules;
        $this->public_modules_functions = $public_modules_functions;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ssm-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ssm-public.js', array( 'jquery' ), $this->version, false );
	}

}
