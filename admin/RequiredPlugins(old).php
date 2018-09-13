<?php

class SSM_Required_Plugins extends SSM_Admin {

	/**
	 * Set up list of required plugins with corresponding options
	 */
	function list_of_required_plugins() {

		$plugins_args = array();

		// array_push( $plugins_args, array(
		// 	"plugin_name"	=> "Radio Buttons for Taxonomies",
		// 	"plugin_slug"	=> "radio-buttons-for-taxonomies/radio-buttons-for-taxonomies.php",
		// 	"type"			=> "error",
		// 	"plugin_link" 	=> "https://wordpress.org/plugins/radio-buttons-for-taxonomies/"
		// ) );

		// array_push( $plugins_args, array(
		// 	"plugin_name"	=> "Gutenberg",
		// 	"plugin_slug"	=> "gutenberg/gutenberg.php",
		// 	"type"			=> "error",
		// 	"plugin_link" 	=> "https://wordpress.org/plugins/gutenberg/"
		// ) );

		// array_push( $plugins_args,  array(
		// 	"plugin_name"	=> "Akismet",
		// 	"plugin_slug" 	=> "akismet/akismet.php",
		// 	"type"			=> "warning",
		// 	"plugin_link"	=> "https://wordpress.org/plugins/akismet/"
		// ) );

		// new plugins go here...

		if ( !empty( $plugins_args ) ) {
			do_action( 'custom_required_plugins_hook', $plugins_args );
		}
		
	}

	/**
	 *
	 * This function is checking whether the required plugins was installed and are active.
	 *
	 * 
	 * @since    1.0.0
	 */
	public function check_required_plugins( $args ) {

		$plugin_domain = get_plugin_data( plugin_dir_path(__FILE__) )['TextDomain'];
		$plugin_name = "{$plugin_domain}/{$plugin_domain}.php"; 

		if ( !empty( $args ) ) {

			foreach ( $args as $arg ) {

				if ( is_admin() && current_user_can( 'activate_plugins' ) && ( !is_plugin_active( $arg['plugin_slug'] ) ) ) {
					
					if ( $arg['type'] == "error" ) {

						deactivate_plugins( $plugin_name ); 
			
						if ( isset( $_GET['activate'] ) ) {
							unset( $_GET['activate'] );
						}

						echo "<div class='notice notice-error'><p>Plugin cannot be activated, it <b>requires</b> <a href='" . $arg['plugin_link'] . "' target='_blank'>" . $arg['plugin_name'] .  "</a> plugin to be installed and active. You can install it directly from the <a href='/wp-admin/plugin-install.php?tab=search&s={$arg['plugin_name']}' target='_blank'>Plugin Directory</a></p></div>";
					
					} else {
						
						echo "<div class=' notice notice-warning'><p>It is <b>recommended</b> to install and activate <a href='" . $arg['plugin_link'] . "' target='_blank'>" . $arg['plugin_name'] . "</a> plugin. You can install it directly from the <a href='/wp-admin/plugin-install.php?tab=search&s={$arg['plugin_name']}' target='_blank'>Plugin Directory</a></p></div>";
					
					}
			
				}
			}
		}

	}

}