<?php

class SSM_Core_Functionality_Admin_Branding extends SSM_Core_Functionality_Admin {

    /**
	 * Makes the login screen's logo link to your homepage, instead of to WordPress.org.
	 * @since 1.0.0
	 */
	public function login_headerurl() {
		return home_url();
	}

	/**
	 * Makes the login screen's logo title attribute your site title, instead of 'WordPress'.
	 * @since 1.0.0
	 */
	public function login_headertitle() {
		return get_bloginfo( 'name' );
	}

		/**
	 * Replaces the login screen's WordPress logo with the 'login-logo.png' in your child theme images folder.
	 * Disabled by default. Make sure you have a login logo before using this function!
	 * Updated 2.0.1: Assumes SVG logo by default
	 * @since 1.0.0
	 */
	public function login_logo() {

		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		
		$background_image =  $this->ssm_get_option('ssm_core_login_logo') != NULL ? $this->ssm_get_option('ssm_core_login_logo') : $defaultLogo;
		$height =  $this->ssm_get_option('ssm_core_login_logo_height') != NULL ? $this->ssm_get_option('ssm_core_login_logo_height') : '128px';
		$width =  $this->ssm_get_option('ssm_core_login_logo_width') != NULL ? $this->ssm_get_option('ssm_core_login_logo_width') : '150px';
		
        ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url(<?php echo $background_image; ?>) !important;
                background-repeat: no-repeat;
                background-size: cover;
                height: <?php echo $height; ?>;
                margin-bottom: 15px;
                width: <?php echo $width; ?>;
            }
        </style>
        <?php
    }

	/**
	 * Makes WordPress-generated emails appear 'from' your WordPress site name, instead of from 'WordPress'.
	 * @since 1.0.0
	 */
	public function mail_from_name() {
		return get_option( 'blogname' );
	}

	/**
	 * Makes WordPress-generated emails appear 'from' your WordPress admin email address.
	 * Disabled by default, in case you don't want to reveal your admin email.
	 * @since 1.0.0
	 */
	public function wp_mail_from() {
		return get_option( 'admin_email' );
	}

	/**
	 * Removes the WP icon from the admin bar
	 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
	 * @since 1.0.0
	 */
	public function remove_icon_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	/**
	 * Modify the admin footer text
	 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
	 * @since 1.0.0
	 */
	function admin_footer_text() {

		$footer_text = get_option('ssm_core_agency_name') != NULL ? get_option('ssm_core_agency_name') : 'Secret Stache Media';
		$footer_link = get_option('ssm_core_agency_url') != NULL ? get_option('ssm_core_agency_url') : 'http://secretstache.com';

		echo 'Built by <a href="' . $footer_link . '" target="_blank">' . $footer_text . '</a> with WordPress.';
	}

}