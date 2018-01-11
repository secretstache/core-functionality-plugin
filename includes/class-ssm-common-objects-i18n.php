<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    Ssm_Common_Objects
 * @subpackage Ssm_Common_Objects/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ssm_Common_Objects
 * @subpackage Ssm_Common_Objects/includes
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class Ssm_Common_Objects_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ssm-common-objects',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
