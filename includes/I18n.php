<?php

namespace SSM\Includes;

/**
 * Define the internationalization functionality.
 */
class I18n {

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ssm',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/admin/languages/'
		);

	}

}
