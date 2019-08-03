<?php

namespace SSM\Includes;

class SSMCLI
{

	public function test() {
		\WP_CLI::success("test");

		// WP_CLI::error( 'Message' )
		// WP_CLI::log( 'Message' )
		// WP_CLI::debug( 'Message' )
	}

}
