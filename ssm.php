<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       SSM Core Functionality Plugin
 * Plugin URI:        https://www.secretstache.com/
 * Description:       SSM Core Functionality Plugin
 * Version:           1.0.0
 * Author:            Secret Stache Media
 * Author URI:        https://www.secretstache.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ssm
 * Domain Path:       /includes/languages
 */

namespace SSM;

use SSM\Includes\Root;
use SSM\Includes\Activator;
use SSM\Includes\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define plugin constants
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );
define( 'SSMC_VERSION', '0.2.3' );
define( 'SSMC_URL', trailingslashit ( plugin_dir_url( __FILE__ ) ) );
define( 'SSMC_DIR', plugin_dir_path( __FILE__ ) );
define( 'SSMC_ADMIN_URL', trailingslashit ( plugin_dir_url( __FILE__ ) . 'admin/' ) );
define( 'SSMC_PUBLIC_URL', trailingslashit ( plugin_dir_url( __FILE__ ) . 'front/' ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/Activator.php
 * 
 */
function activate_ssm() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Deactivator.php
 */
function deactivate_ssm() {
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ssm' );
register_deactivation_hook( __FILE__, 'deactivate_ssm' );

/**
 * Execution of the plugin.
 */
function run_ssm() {

	// Require composer's autoload file
	require_once( plugin_dir_path( __FILE__ ) . "vendor/autoload.php" );

	$root = new Root();
	$root->run();

}

run_ssm();