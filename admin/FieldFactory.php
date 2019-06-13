<?php

namespace SSM\Admin;

class FieldFactory
{

    /**
	 * Set up directory where to [save] JSON
	 */
    public function saveJSON()
    {
		return SSMC_DIR . "/acf";
	}

	/**
	 * Set up directory where to [load] JSON from
	 */
    public function loadJSON( $paths )
    {
		$paths[] = SSMC_DIR . "/acf";
		return $paths;
	}

}
