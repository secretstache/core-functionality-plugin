<?php

namespace SSM\Admin;

use SSM\Includes\Helpers as SSMH;

class OptionsPage
{
    
    /** 
     * Register SSM Core Settings
     */
    public function ssmCoreSettings()
    {

		register_setting( 'ssm-core-settings-group', 'ssm_core_acf_admin_users' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_name' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_agency_url' );
	
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_width' );
		register_setting( 'ssm-core-settings-group', 'ssm_core_login_logo_height' );
	
		add_settings_section( 'ssm-core-agency-options', 'Agency Options', array( $this, 'ssmCoreAgencyOptions'), 'ssm_core');
	
		add_settings_field( 'ssm-core-agency-name', 'Agency Name', array( $this, 'ssmCoreAgencyName' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-agency-url', 'Agency URL', array( $this, 'ssmCoreAgencyUrl' ), 'ssm_core', 'ssm-core-agency-options' );
        add_settings_field( 'ssm-core-login-logo', 'Login Logo', array( $this, 'ssmCoreLoginLogo' ), 'ssm_core', 'ssm-core-agency-options' );
        
        add_settings_section( 'ssm-core-acf-options', 'ACF Options', array( $this, 'ssm_acf_options' ), 'ssm_core' );

        add_settings_field(
            'ssm-core-acf-admin-users',
            'Admin users who need access to ACF',
            array( $this, 'ssm_core_acf_admin_users' ),
            'ssm_core',
            'ssm-core-acf-options',
            [ 'admins' => get_users( array('role' => 'administrator') ) ]
        );

    }

    /** 
     * Add Admin users who need access to ACF field 
     */
    function ssm_core_acf_admin_users( $args ) {

        $admins = $args['admins'];
        $acfAdmins = get_option('ssm_core_acf_admin_users') != NULL ? get_option('ssm_core_acf_admin_users') : array();

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

    /** 
     * Add "Agency Name" field 
     */
    public function ssmCoreAgencyName()
    {

        $agency_name = get_option('ssm_core_agency_name') != NULL ? esc_attr( get_option('ssm_core_agency_name') ) : 'Secret Stache Media';
		echo '<input type="text" name="ssm_core_agency_name" value="' . $agency_name . '" class="regular-text"/>';

    }
    
    /** 
     * Add "Agency URL" field 
     */
    public function ssmCoreAgencyUrl()
    {

        $agency_URL = get_option('ssm_core_agency_url') != NULL ? esc_attr( get_option('ssm_core_agency_url') ) : 'http://secretstache.com';
		echo '<input type="text" name="ssm_core_agency_url" value="' . $agency_URL . '" class="regular-text url"/>';
		echo '<p class="description">Include <code>http(s)://</code></p>';

    }
    
    /** 
     * Add "Agency Logo" field 
     */
    public function ssmCoreLoginLogo()
    {
        $default_logo = SSMC_ADMIN_URL . 'images/login-logo.png';
        $login_logo = get_option('ssm_core_login_logo') != NULL ? esc_attr( get_option('ssm_core_login_logo') ) : $default_logo;
		$width = get_option('ssm_core_login_logo_width') != NULL ? esc_attr( get_option('ssm_core_login_logo_width') ) : '230px';
		$height = get_option('ssm_core_login_logo_height') != NULL ? esc_attr( get_option('ssm_core_login_logo_height') ) : 'auto';

		echo '<div class="login-logo-wrap">';
		echo '<img src="' . $login_logo . '" id="logo-preview" class="login-logo" alt="Login Logo" style="height: ' . $height . '; width: ' . $width . '; "/>';
		echo '<div class="media-buttons">';
		echo '<input type="button" id="upload-image-button" class="button button-secondary" value="Upload Logo" />';
		echo '<input type="button" id="remove-image-button" class="button button-secondary" value="Remove Logo" />';
		echo '</div>';
		echo '<input type="hidden" id="ssm-core-login-logo" name="ssm_core_login_logo" value="' . $login_logo . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-width" name="ssm_core_login_logo_width" value="' . $width . '">';
		echo '<input type="hidden" id="ssm-core-login-logo-height" name="ssm_core_login_logo_height" value="' . $height . '">';
		echo '</div>';
	}
    
    /** 
     * Add Options Page 
     */
    public function addSsmOptionsPage()
    {
	
		add_submenu_page(
		'options-general.php',
		  'SSM Core', // page title
		  'Core', // menu title
		'manage_options',
		'ssm_core',
		array( $this, 'ssmCoreOptionsPage' )
	  );
	
	}
    
    /** 
     * Add "Agency Name" field - include template 
     */
    public function ssmCoreOptionsPage()
    {
        ?>

        <div class="wrap">

            <?php if ( get_option('ssm_core_agency_name') ) { ?>

                <h1><?php echo get_option('ssm_core_agency_name'); ?> Admin Core</h1>

            <?php } else { ?>

                <h1>Admin Core</h1>

            <?php } ?>

            <div class="core-settings-form">

                <form method="post" action="options.php">

                    <?php settings_fields( 'ssm-core-settings-group' ); ?>
                    <?php do_settings_sections( 'ssm_core' ); ?>

                    <?php submit_button(); ?>

                </form>

            </div>

            </div>


        <?php

    }

    /** 
     * Empty functions we are obligatory to leave here 
     * since they are callbacks for field declarations 
     */
    public function ssmCoreAgencyOptions() {}
    public function ssmCoreAdminModules() {}
    public function ssmCoreFrontModules() {}
    public function ssmCoreHelpers() {}
    public function ssm_acf_options() {}

}