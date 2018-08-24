<?php

class SSM_Core_Functionality_options extends SSM_Core_Functionality_Admin {

    /** 
     * Register SSM Core Settings
     *  
     */
    public function ssm_core_settings() {

		register_setting( 'ssm-core-settings-group', 'ssm_core_acf_admin_users' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_name' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_url' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_width' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_height' );
	
	
		if ( current_theme_supports( 'ssm-admin-branding' ) ) {
			add_settings_section( 'ssm-core-agency-options', 'Agency Options', array( $this, 'ssm_core_agency_options'), 'ssm_core');
		}
	
		add_settings_field( 'ssm-core-agency-name', 'Agency Name', array( $this, 'ssm_core_agency_name' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-agency-url', 'Agency URL', array( $this, 'ssm_core_agency_url' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-login-logo', 'Login Logo', array( $this, 'ssm_core_login_logo' ), 'ssm_core', 'ssm-core-agency-options' );
	
		
		if ( current_theme_supports( 'ssm-acf' ) ) {
			add_settings_section( 'ssm-core-acf-options', 'ACF Options', array( $this,  'ssm_acf_options' ), 'ssm_core' );
		}
	
		add_settings_field(
			'ssm-core-acf-admin-users',
			'Admin users who need access to ACF',
			array( $this, 'ssm_core_acf_admin_users' ),
			'ssm_core',
			'ssm-core-acf-options',
			[
				'admins' => get_users( array('role' => 'administrator') )
			]
		);
	}
	
	
	public function ssm_core_agency_options() {

	}
	
	public function ssm_core_agency_name() {
		$agencyName = $this->ssm_get_option('ssm_core_agency_name') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_agency_name') ) : 'Secret Stache Media';
		echo '<input type="text" name="ssm_core_agency_name" value="' . $agencyName . '" class="regular-text"/>';
	}
	
	public function ssm_core_agency_url() {
		$agencyURL = $this->ssm_get_option('ssm_core_agency_url') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_agency_url') ) : 'http://secretstache.com';
		echo '<input type="text" name="ssm_core_agency_url" value="' . $agencyURL . '" class="regular-text url"/>';
		echo '<p class="description">Include <code>http(s)://</code></p>';
	}
	
	public function ssm_core_login_logo() {
		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		$loginLogo = $this->ssm_get_option('ssm_core_login_logo') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo') ) : $defaultLogo;
		$width = $this->ssm_get_option('ssm_core_login_logo_width') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo_width') ) : '230px';
		$height = $this->ssm_get_option('ssm_core_login_logo_height') != NULL ? esc_attr( $this->ssm_get_option('ssm_core_login_logo_height') ) : 'auto';
	
		echo '<div class="login-logo-wrap">';
		echo '<img src="' . $loginLogo . '" id="logo-preview" class="login-logo" alt="Login Logo" style="height: ' . $height . '; width: ' . $width . '; "/>';
		echo '<div class="media-buttons">';
		echo '<input type="button" id="upload-image-button" class="button button-secondary" value="Upload Logo" />';
		echo '<input type="button" id="remove-image-button" class="button button-secondary" value="Remove Logo" />';
		echo '</div>';
		echo '<input type="hidden" id="ssm-core-login-logo" name="ssm_core_login_logo" value="' . $loginLogo . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-width" name="ssm_core_login_logo_width" value="' . $width . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-height" name="ssm_core_login_logo_height" value="' . $height . '">';
		echo '</div>';
	}
	
	public function ssm_acf_options() {
	
	}
	
	public function ssm_core_acf_admin_users( $args ) {
		$admins = $args['admins'];
		$acfAdmins = $this->ssm_get_option('ssm_core_acf_admin_users') != NULL ? $this->ssm_get_option('ssm_core_acf_admin_users') : array();
	
		?>
		<select id="ssm-core-acf-admin-users" name="ssm_core_acf_admin_users[]" multiple style="min-width: 200px;">
			<?php foreach ( $admins as $admin ) { ?>
				<?php $selected = in_array( $admin->ID, $acfAdmins ) ? ' selected' : ''; ?>
				<option value="<?php echo $admin->ID; ?>"<?php echo $selected; ?>>
					<?php echo $admin->user_login; ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	public function add_ssm_options_page() {

		if ( ! current_theme_supports('ssm-admin-branding') && ! current_theme_supports('ssm-admin-branding') )
		  return;
	
		add_submenu_page(
		'options-general.php',
		  'SSM Core', // page title
		  'Core', // menu title
		'manage_options',
		'ssm_core',
		array( $this, 'ssm_core_options_page' )
	  );
	
	}
	
	public function ssm_core_options_page() {
		require_once( SSMC_DIR . 'admin/templates/admin-options.php' );
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
     * Load Admin Scripts
     *  
     */
	public function load_admin_scripts( $hook ) {

		if ( $hook != 'settings_page_ssm_core' )
			return;
	
		wp_register_style( 'ssm-core-admin-css', SSMC_ADMIN_URL . 'css/admin.css', array(), SSMC_VERSION , 'all' );
		wp_enqueue_style( 'ssm-core-admin-css' );
	
		wp_enqueue_media();
	
		wp_register_script( 'ssm-core-admin-js', SSMC_ADMIN_URL . 'js/admin.js', array('jquery'), SSMC_VERSION, true );
	
		$login_logo_array = array(
			'url' => SSMC_ADMIN_URL . 'images/login-logo.png',
		);
	
		wp_localize_script( 'ssm-core-admin-js', 'login_logo', $login_logo_array );
	
		wp_enqueue_script( 'ssm-core-admin-js' );
	
	}

}