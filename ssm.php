<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.secretstache.com/
 * @since             1.0.0
 * @package           SSM
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
 * Domain Path:       /languages
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
define( 'SSMC_PUBLIC_URL', trailingslashit ( plugin_dir_url( __FILE__ ) . 'public/' ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ssm-activator.php
 */
function activate_ssm() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ssm-deactivator.php
 */
function deactivate_ssm() {
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ssm' );
register_deactivation_hook( __FILE__, 'deactivate_ssm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ssm() {

	require_once( plugin_dir_path( __FILE__ ) . "vendor/autoload.php" );

	$root = new Root();
	$root->run();

}

run_ssm();