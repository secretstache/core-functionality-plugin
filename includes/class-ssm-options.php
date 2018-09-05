<?php

class SSM_Options {

    /**
     * Initialize the class and set its properties.
     *
	 * @since    1.0.0
	 */
	public function __construct( $public_modules, $public_modules_functions, $admin_modules, $admin_modules_functions ) {

        $this->public_modules = $public_modules;
        $this->public_modules_functions = $public_modules_functions;
        $this->admin_modules = $admin_modules;
        $this->admin_modules_functions = $admin_modules_functions;
    }
    
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
	
		add_settings_section( 'ssm-core-agency-options', 'Agency Options', array( $this, 'ssm_core_agency_options'), 'ssm_core');
	
		add_settings_field( 'ssm-core-agency-name', 'Agency Name', array( $this, 'ssm_core_agency_name' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-agency-url', 'Agency URL', array( $this, 'ssm_core_agency_url' ), 'ssm_core', 'ssm-core-agency-options' );
		add_settings_field( 'ssm-core-login-logo', 'Login Logo', array( $this, 'ssm_core_login_logo' ), 'ssm_core', 'ssm-core-agency-options' );
        
        //Admin Area
        add_settings_section( 'ssm-core-admin-modules', 'Admin Area', array( $this, 'ssm_core_admin_modules'), 'ssm_core');
   
        add_settings_field( 'ssm-admin-modules', 'Modules', array( $this, 'ssm_admin_modules' ), 'ssm_core', 'ssm-core-admin-modules' );
        add_settings_field( 'ssm-admin-modules-functions', 'Modules Functions', array( $this, 'ssm_admin_modules_functions' ), 'ssm_core', 'ssm-core-admin-modules' );
        
        //Public Area
        add_settings_section( 'ssm-core-public-modules', 'Public Area', array( $this, 'ssm_core_public_modules'), 'ssm_core');

        add_settings_field( 'ssm-public-modules', 'Modules', array( $this, 'ssm_public_modules' ), 'ssm_core', 'ssm-core-public-modules' );
        add_settings_field( 'ssm-public-modules-functions', 'Modules Functions', array( $this, 'ssm_public_modules_functions' ), 'ssm_core', 'ssm-core-public-modules' );
        
        //Helpers
        add_settings_section( 'ssm-core-helpers', 'Helpers', array( $this, 'ssm_core_helpers'), 'ssm_core');

        add_settings_field( 'ssm-helpers', 'Functions', array( $this, 'ssm_helpers' ), 'ssm_core', 'ssm-core-helpers' );


        // add_settings_field( 'ssm-modules', 'Modules', array( $this, 'ssm_modules' ), 'ssm_core', 'ssm-core-admin-modules' );
        // add_settings_field( 'ssm-modules-functions', 'Modules Functions', array( $this, 'ssm_modules_functions' ), 'ssm_core', 'ssm-core-admin-modules' );

        // add_settings_section( 'ssm-core-acf-options', 'ACF Options', array( $this,  'ssm_acf_options' ), 'ssm_core' );
        // add_settings_field(
		// 	'ssm-core-acf-admin-users',
		// 	'Admin users who need access to ACF',
		// 	array( $this, 'ssm_core_acf_admin_users' ),
		// 	'ssm_core',
		// 	'ssm-core-acf-options',
		// 	[
		// 		'admins' => get_users( array('role' => 'administrator') )
		// 	]
        // );
    }

    public function ssm_admin_modules() {

        $admin_enabled_modules = get_option( 'admin_enabled_modules' );

        echo "<div id='admin_modules'>";
        
        foreach ( $this->admin_modules as $module ) {

            $checked = ( in_array( $module, $admin_enabled_modules ) ) ? 'checked' : '';
            
            echo "<div class='admin_module {$module['slug']}' data-module-slug='{$module['slug']}'>";
            echo "<input type='checkbox' name='{$module['slug']}' id='{$module['slug']}' {$checked} />";
            echo "<label for='{$module['slug']}'> {$module['name']} </label>";
            echo "</div>";
        }

        echo "</div>";
    }
    
    public function ssm_admin_modules_functions() {

        $admin_enabled_functions = get_option( 'admin_enabled_functions' );

        echo "<div id='admin_functions'>";

        foreach ( $this->admin_modules_functions as $slug => $function ) {
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

    public function ssm_public_modules() {
        
        $public_enabled_modules = get_option( 'public_enabled_modules' );

        echo "<div id='public_modules'>";
        
        foreach ( $this->public_modules as $module ) {

            $checked = ( in_array( $module, $public_enabled_modules ) ) ? 'checked' : '';
            
            echo "<div class='public_module {$module['slug']}' data-module-slug='{$module['slug']}'>";
            echo "<input type='checkbox' name='{$module['slug']}' id='{$module['slug']}' {$checked} />";
            echo "<label for='{$module['slug']}'> {$module['name']} </label>";
            echo "</div>";
        }

        echo "</div>";

    }
    
    public function ssm_public_modules_functions() {

        $public_enabled_functions = get_option( 'public_enabled_functions' );

        echo "<div id='public_functions'>";

        foreach ( $this->public_modules_functions as $slug => $function ) {
            echo "<div class='public_function {$slug}' data-module-slug='{$slug}'>";
            echo "<h4 class='module_name'>{$function['module_name']}</h3>";

            foreach ( $function['hooks'] as $hook ) {
                
                if ( is_array( $public_enabled_functions[$slug]['hooks'] ) ) {
                    $checked = ( in_array( $hook, $public_enabled_functions[$slug]['hooks'] ) ) ? 'checked' : '';
                }

                echo "<input type='checkbox' name='{$hook['function']}' id='{$hook['function']}' {$checked} />";
                echo "<label for='{$hook['function']}'> {$hook['function']} </label>";
                echo "<br />";
            }

            echo "</div>";
        }

        echo "</div>";

    }

    public function ssm_helpers() {

        $helpers = get_class_methods('SSMH');

        echo "<div id='helpers'>";
        
        foreach ( $helpers as $helper ) {
  
            echo "<div class='helper {$helper}' data-helper-slug='{$helper}'>";
            echo "<input type='checkbox' name='{$helper}' id='{$helper}' checked onclick=\"return false;\" />";
            echo "<label for='{$helper}'> {$helper} </label>";
            echo "</div>";
        }

        echo "</div>";

    }

    public function handle_options_update() {

        if ( isset( $_POST ) && $_POST['option_page'] == 'ssm-core-settings-group' && $_POST['action'] == 'update' ) {

            $new_admin_modules = array();
            $new_public_modules = array();

            foreach ( $this->admin_modules as $module ) {
                
                if ( $_POST[$module['slug']] == 'on' ) {
                    array_push( $new_admin_modules, $module );
                }

            }

            foreach ( $this->public_modules as $module ) {
                
                if ( $_POST[$module['slug']] == 'on' ) {
                    array_push( $new_public_modules, $module );
                }

            }

            foreach ( $this->admin_modules_functions as $slug => $function ) {

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

            foreach ( $this->public_modules_functions as $slug => $function ) {

                $new_public_functions[$slug] = array();
                $new_public_hooks = array();

                foreach ( $function['hooks'] as $hook ) {

                    if ( $_POST[$hook['function']] == 'on' ) {
                        array_push( $new_public_hooks, $hook );
                    }
                    
                }

                $new_public_functions[$slug]["module_name"] = $function['module_name'];
                $new_public_functions[$slug]["hooks"] = $new_public_hooks;
                
            }

            update_option( 'admin_enabled_modules', $new_admin_modules );
            update_option( 'admin_enabled_functions', $new_admin_functions );
            update_option( 'public_enabled_modules', $new_public_modules );
            update_option( 'public_enabled_functions', $new_public_functions );

        }
    }
	
	public function ssm_core_agency_options() {}
    
    public function ssm_core_admin_modules() {}
    public function ssm_core_public_modules() {}
    public function ssm_core_helpers() {}

	public function ssm_core_agency_name() {
		$agencyName = get_option('ssm_core_agency_name') != NULL ? esc_attr( get_option('ssm_core_agency_name') ) : 'Secret Stache Media';
		echo '<input type="text" name="ssm_core_agency_name" value="' . $agencyName . '" class="regular-text"/>';
	}
	
	public function ssm_core_agency_url() {
		$agencyURL = get_option('ssm_core_agency_url') != NULL ? esc_attr( get_option('ssm_core_agency_url') ) : 'http://secretstache.com';
		echo '<input type="text" name="ssm_core_agency_url" value="' . $agencyURL . '" class="regular-text url"/>';
		echo '<p class="description">Include <code>http(s)://</code></p>';
	}
	
	public function ssm_core_login_logo() {
		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		$loginLogo = get_option('ssm_core_login_logo') != NULL ? esc_attr( get_option('ssm_core_login_logo') ) : $defaultLogo;
		$width = get_option('ssm_core_login_logo_width') != NULL ? esc_attr( get_option('ssm_core_login_logo_width') ) : '230px';
		$height = get_option('ssm_core_login_logo_height') != NULL ? esc_attr( get_option('ssm_core_login_logo_height') ) : 'auto';
	
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
	
	public function ssm_acf_options() {}
	
	public function ssm_core_acf_admin_users( $args ) {
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
	
	public function add_ssm_options_page() {
	
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

}