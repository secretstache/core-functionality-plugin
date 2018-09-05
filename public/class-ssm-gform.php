<?php

class SSM_GForm extends SSM_Public {

    /**
     * Force Gravity Forms to init scripts in the footer and ensure that the DOM is loaded before scripts are executed
	 * @since 1.0.0
	 */
    public function footer_scripts_init() {
        return true;
    }

    /**
     * Wrap Gform - CData open
	 * @since 1.0.0
	 */
    public function wrap_gform_cdata_open( $content = '' ) {
        
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
        
        $content = 'document.addEventListener( "DOMContentLoaded", function() { ';
        
        return $content;
    
    }

    /**
     * Wrap Gform - CData close
	 * @since 1.0.0
	 */
    public function wrap_gform_cdata_close( $content = '' ) {
   
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
    
        $content = ' }, false );';
    
        return $content;
    
    }

}


