<?php

class SSM_Core_Functionality_options extends SSM_Core_Functionality_Admin {

    /**
	 * The list of modules to be included in the core
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $modules;

    /**
	 * The array of arguments in accordance with corresponding core modules
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
    protected $modules_functions;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
     * @access  public
	 */
    public function __construct( $plugin_name, $plugin_version, $modules, $modules_functions ) {

        $this->modules = $modules;
        $this->modules_functions = $modules_functions;
        $this->set_initial_options();
    }

    /**
	 * Set up initial state of the main options (enable all of the modules and features).
	 *
	 * @since   1.0.0
     * @access  public
	 */
    public function set_initial_options() {

        $ssm_enabled_functions = get_option( 'ssm_enabled_functions' );

        if ( !get_option( 'ssm_enabled_modules' ) ) {
            add_option( 'ssm_enabled_modules' );
            update_option('ssm_enabled_modules', $this->modules, true);
        }

        if ( !get_option( 'ssm_enabled_functions' ) ) {
            add_option( 'ssm_enabled_functions' );
            update_option('ssm_enabled_functions', $this->modules_functions, true);
        }

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
                
        add_settings_field( 'ssm-modules', 'Modules', array( $this, 'ssm_modules' ), 'ssm_core', 'ssm-core-agency-options' );
        add_settings_field( 'ssm-modules-functions', 'Modules Functions', array( $this, 'ssm_modules_functions' ), 'ssm_core', 'ssm-core-agency-options' );
	
    }

    public function ssm_modules() {

        $ssm_enabled_modules = get_option( 'ssm_enabled_modules' );

        echo "<div id='ssm_modules'>";
        
        foreach ( $this->modules as $module ) {

            $checked = ( in_array( $module, $ssm_enabled_modules ) ) ? 'checked' : '';
            
            echo "<div class='ssm_module {$module['slug']}' data-module-slug='{$module['slug']}'>";
            echo "<input type='checkbox' name='{$module['slug']}' id='{$module['slug']}' {$checked} />";
            echo "<label for='{$module['slug']}'> {$module['name']} </label>";
            echo "</div>";
        }

        echo "</div>";
    }
    
    public function ssm_modules_functions() {

        $ssm_enabled_functions = get_option( 'ssm_enabled_functions' );

        echo "<div id='ssm_functions'>";

        foreach ( $this->modules_functions as $slug => $function ) {
            echo "<div class='ssm_function {$slug}' data-module-slug='{$slug}'>";
            echo "<h4 class='module_name'>{$function['module_name']}</h3>";

            foreach ( $function['hooks'] as $hook ) {
                
                if ( is_array( $ssm_enabled_functions[$slug]['hooks'] ) ) {
                    $checked = ( in_array( $hook, $ssm_enabled_functions[$slug]['hooks'] ) ) ? 'checked' : '';
                }

                echo "<input type='checkbox' name='{$hook['function']}' id='{$hook['function']}' {$checked} />";
                echo "<label for='{$hook['function']}'> {$hook['function']} </label>";
                echo "<br />";
            }

            echo "</div>";
        }

        echo "</div>";
    }

    public function handle_options_update() {

        if ( isset( $_POST ) && $_POST['option_page'] == 'ssm-core-settings-group' && $_POST['action'] == 'update' ) {

            $new_modules = array();

            foreach ( $this->modules as $module ) {
                
                if ( $_POST[$module['slug']] == 'on' ) {
                    array_push( $new_modules, $module );
                }

            }

            foreach ( $this->modules_functions as $slug => $function ) {

                $new_functions[$slug] = array();
                $new_hooks = array();

                foreach ( $function['hooks'] as $hook ) {

                    if ( $_POST[$hook['function']] == 'on' ) {
                        array_push( $new_hooks, $hook );
                    }
                    
                }

                $new_functions[$slug]["module_name"] = $function['module_name'];
                $new_functions[$slug]["hooks"] = $new_hooks;
                
            }

            update_option( 'ssm_enabled_modules', $new_modules );
            update_option( 'ssm_enabled_functions', $new_functions );

        }
    }
	
	public function ssm_core_agency_options() {}
	
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
	
	public function ssm_acf_options() {}
	
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