<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.secretstache.com/
 * @since             1.0.0
 * @package           Ssm_Common_Objects
 *
 * @wordpress-plugin
 * Plugin Name:       SSM Common Objects
 * Plugin URI:        https://www.secretstache.com/
 * Description:       Enables a set of Custom Post Types (Team, Testimonial, Project, Code snippet)
 * Version:           1.0.0
 * Author:            Secret Stache Media
 * Author URI:        https://www.secretstache.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ssm-common-objects
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ssm-common-objects-activator.php
 */
function activate_ssm_common_objects() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ssm-common-objects-activator.php';
	Ssm_Common_Objects_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ssm-common-objects-deactivator.php
 */
function deactivate_ssm_common_objects() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ssm-common-objects-deactivator.php';
	Ssm_Common_Objects_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ssm_common_objects' );
register_deactivation_hook( __FILE__, 'deactivate_ssm_common_objects' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ssm-common-objects.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ssm_common_objects() {

	$plugin = new Ssm_Common_Objects();
	$plugin->run();

}
run_ssm_common_objects();
