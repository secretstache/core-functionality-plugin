<?php

namespace SSM\Admin;

class RequiredPlugins
{

	/**
	 * This function is loading .json file with the list of required plugins and its configuration
	 */
    public function checkRequiredPlugins()
    {
        return SSMC_INCLUDES_DIR . '/json/bundle.json';
	}

}