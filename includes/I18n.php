<?php

namespace SSM\Includes;

/**
 * Define the internationalization functionality.
 */
class I18n 
{

	/**
	 * Load the plugin text domain for translation.
	 */
	public function loadPluginTextdomain()
	{

		load_plugin_textdomain(
			LOCALIZATION_ID,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/admin/languages/'
		);

	}

}
