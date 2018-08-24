<?php

class SSM_Core_Functionality_Field_Factory extends SSM_Core_Functionality_Admin {

	/**
	 * Set up ACF JSON directory
	 * @since 1.0.0
	 */
    public function ssm_save_json() {
		return SSMC_DIR . '/acf';
	}

	/**
	 * Save JSON files from the directory
	 * @since 1.0.0
	 */
	public function ssm_save_folder_json( $folders ) {
		$folders['Field Factory'] = SSMC_DIR . '/acf';
		return $folders;
	}

	/**
	 * Load JSON files from the directory
	 * @since 1.0.0
	 */
	public function ssm_load_json( $paths ) {
		$paths[] = SSMC_DIR . '/acf';
		return $paths;
	}

	/**
	 * Hide Advanced Custom Fields to Users
	 * @since 1.0.0
	 */
	public function remove_acf_menu() {

		// provide a list of usernames who can edit custom field definitions here
		$acfAdmins = get_option('ssm_core_acf_admin_users') != NULL ? get_option('ssm_core_acf_admin_users') : array(1);
	
		// get the current user
		$current_user = wp_get_current_user();
	
		if ( $acfAdmins != NULL ) {
	
		// match and remove if needed
		if ( !in_array( $current_user->ID, $acfAdmins ) ) {
			remove_menu_page('edit.php?post_type=acf-field-group');
		}
	
		}
	}

}