<?php

namespace SSM\Admin;

class RequiredPlugins
{

	/**
	 * This function is loading .json file with the list of required plugins and its configuration
	 */
    public function checkRequiredPlugins()
    {
        return SSMC_DIR . 'includes/json/bundle.json';
	}

}