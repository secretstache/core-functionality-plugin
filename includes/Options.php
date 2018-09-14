<?php

namespace SSM\Includes;

use SSM\Includes\Helpers as SSMH;

class Options
{

    /**
     * Initialize the class and set its properties.
     *
	 * @since    1.0.0
	 */
    public function __construct( $frontModules, $frontModuleFunctions, $adminModules, $adminModuleFunctions )
    {
        $this->frontModules = $frontModules;
        $this->frontModuleFunctions = $frontModuleFunctions;
        $this->adminModules = $adminModules;
        $this->adminModuleFunctions = $adminModuleFunctions;
    }
    
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
        
        //Admin Area
        add_settings_section( 'ssm-core-admin-modules', 'Admin Area', array( $this, 'ssmCoreAdminModules'), 'ssm_core');
   
        add_settings_field( 'ssm-admin-modules', 'Modules', array( $this, 'ssmAdminModules' ), 'ssm_core', 'ssm-core-admin-modules' );
        add_settings_field( 'ssm-admin-module-functions', 'Module Functions', array( $this, 'ssmAdminModuleFunctions' ), 'ssm_core', 'ssm-core-admin-modules' );
        
        //Front Area
        add_settings_section( 'ssm-core-front-modules', 'Front Area', array( $this, 'ssmCoreFrontModules'), 'ssm_core');

        add_settings_field( 'ssm-front-modules', 'Modules', array( $this, 'ssmFrontModules' ), 'ssm_core', 'ssm-core-front-modules' );
        add_settings_field( 'ssm-front-module-functions', 'Module Functions', array( $this, 'ssmFrontModuleFunctions' ), 'ssm_core', 'ssm-core-front-modules' );
        
        //Helpers
        add_settings_section( 'ssm-core-helpers', 'Helpers', array( $this, 'ssmCoreHelpers'), 'ssm_core');

        add_settings_field( 'ssm-helpers', 'Functions', array( $this, 'ssmHelpers' ), 'ssm_core', 'ssm-core-helpers' );

    }

    /** 
     * Add "Admin Modules" section
     */
    public function ssmAdminModules()
    {

        $admin_enabled_modules = get_option( 'admin_enabled_modules' );

        echo "<div id='admin_modules'>";
        
        foreach ( $this->adminModules as $module ) {

            $checked = ( in_array( $module, $admin_enabled_modules ) ) ? 'checked' : '';
            
            echo "<div class='admin_module {$module['slug']}' data-module-slug='{$module['slug']}'>";
            echo "<input type='checkbox' name='{$module['slug']}' id='{$module['slug']}' {$checked} />";
            echo "<label for='{$module['slug']}'> {$module['name']} </label>";
            echo "</div>";
        }

        echo "</div>";
    }
    
    /** 
     * Add "Admin Module Functions" section
     */
    public function ssmAdminModuleFunctions()
    {

        $admin_enabled_functions = get_option( 'admin_enabled_functions' );

        echo "<div id='admin_functions'>";

        foreach ( $this->adminModuleFunctions as $slug => $function ) {
            echo "<div class='admin_function {$slug}' data-module-slug='{$slug}'>";
            echo "<h4 class='module_name'>{$function['module_name']}</h3>";

            foreach ( $function['hooks'] as $hook ) {
                
                if ( is_array( $admin_enabled_functions[$slug]['hooks'] ) ) {
                    $checked = ( in_array( $hook, $admin_enabled_functions[$slug]['hooks'] ) ) ? 'checked' : '';
                }

                echo "<input type='checkbox' name='{$hook['function']}' id='{$hook['function']}' {$checked} />";
                echo "<label for='{$hook['function']}'> {$hook['function']} </label>";
                echo "<br />";
            }

            echo "</div>";
        }

        echo "</div>";
    }

    /** 
     * Add "Front Modules" section
     */
    public function ssmFrontModules()
    {
        
        $front_enabled_modules = get_option( 'front_enabled_modules' );

        echo "<div id='front_modules'>";
        
        foreach ( $this->frontModules as $module ) {

            $checked = ( in_array( $module, $front_enabled_modules ) ) ? 'checked' : '';
            
            echo "<div class='front_module {$module['slug']}' data-module-slug='{$module['slug']}'>";
            echo "<input type='checkbox' name='{$module['slug']}' id='{$module['slug']}' {$checked} />";
            echo "<label for='{$module['slug']}'> {$module['name']} </label>";
            echo "</div>";
        }

        echo "</div>";

    }
    
    /** 
     * Add "Front Module Functions" section
     */
    public function ssmFrontModuleFunctions()
    {

        $front_enabled_functions = get_option( 'front_enabled_functions' );

        echo "<div id='front_functions'>";

        foreach ( $this->frontModuleFunctions as $slug => $function ) {
            echo "<div class='front_function {$slug}' data-module-slug='{$slug}'>";
            echo "<h4 class='module_name'>{$function['module_name']}</h3>";

            foreach ( $function['hooks'] as $hook ) {
                
                if ( is_array( $front_enabled_functions[$slug]['hooks'] ) ) {
                    $checked = ( in_array( $hook, $front_enabled_functions[$slug]['hooks'] ) ) ? 'checked' : '';
                }

                echo "<input type='checkbox' name='{$hook['function']}' id='{$hook['function']}' {$checked} />";
                echo "<label for='{$hook['function']}'> {$hook['function']} </label>";
                echo "<br />";
            }

            echo "</div>";
        }

        echo "</div>";

    }

    /** 
     * Add "Helpers" section
     */
    public function ssmHelpers()
    {

        $helpers = get_class_methods('SSM\Includes\Helpers');

        echo "<div id='helpers'>";
        
        foreach ( $helpers as $helper ) {
  
            echo "<div class='helper {$helper}' data-helper-slug='{$helper}'>";
            echo "<input type='checkbox' name='{$helper}' id='{$helper}' checked onclick=\"return false;\" />";
            echo "<label for='{$helper}'> {$helper} </label>";
            echo "</div>";
        }

        echo "</div>";

    }

    /** 
     * Handle user's input while updating an option values
     */
    public function handleOptionsUpdate()
    {

        if ( isset( $_POST['option_page'] ) && $_POST['option_page'] == 'ssm-core-settings-group' && $_POST['action'] == 'update' ) {

            $new_admin_modules = array();
            $new_front_modules = array();

            foreach ( $this->adminModules as $module ) {
                
                if ( $_POST[$module['slug']] == 'on' ) {
                    array_push( $new_admin_modules, $module );
                }

            }

            foreach ( $this->frontModules as $module ) {
                
                if ( $_POST[$module['slug']] == 'on' ) {
                    array_push( $new_front_modules, $module );
                }

            }

            foreach ( $this->adminModuleFunctions as $slug => $function ) {

                $new_admin_functions[$slug] = array();
                $new_admin_hooks = array();

                foreach ( $function['hooks'] as $hook ) {

                    if ( $_POST[$hook['function']] == 'on' ) {
                        array_push( $new_admin_hooks, $hook );
                    }
                    
                }

                $new_admin_functions[$slug]["module_name"] = $function['module_name'];
                $new_admin_functions[$slug]["hooks"] = $new_admin_hooks;
                
            }

            foreach ( $this->frontModuleFunctions as $slug => $function ) {

                $new_front_functions[$slug] = array();
                $new_front_hooks = array();

                foreach ( $function['hooks'] as $hook ) {

                    if ( $_POST[$hook['function']] == 'on' ) {
                        array_push( $new_front_hooks, $hook );
                    }
                    
                }

                $new_front_functions[$slug]["module_name"] = $function['module_name'];
                $new_front_functions[$slug]["hooks"] = $new_front_hooks;
                
            }

            update_option( 'admin_enabled_modules', $new_admin_modules );
            update_option( 'admin_enabled_functions', $new_admin_functions );
            update_option( 'front_enabled_modules', $new_front_modules );
            update_option( 'front_enabled_functions', $new_front_functions );

        }
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
        require_once( SSMC_DIR . 'admin/templates/admin-options.php' );
    }

    /** 
     * Empty functions we are obligatory to leave here 
     * since they are callbacks for field declarations 
     */
    public function ssmCoreAgencyOptions() {}
    public function ssmCoreAdminModules() {}
    public function ssmCoreFrontModules() {}
    public function ssmCoreHelpers() {}

}