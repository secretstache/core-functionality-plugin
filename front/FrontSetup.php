<?php

namespace SSM\Front;

use SSM\Includes\Helpers as SSMH;

class FrontSetup
{

    /**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style( 'ssm', plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script( 'ssm', plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
    }
    
    /**
     * Show current year as a shortcode
	 */
    public function addYearShortcode()
    {
        add_shortcode('year', array( $this, 'addYearShortcodeCB' ) );
    }
    
    /**
     * addYearShortcode() callback
	 */
    public function addYearShortcodeCB()
    {
        
        $year = date('Y');
        return $year;
    
    }

    /**
     * Set Favicon
     */
    public function setFavicon()
    {

        if ( $favicon = get_field('favicon', 'options') ) {
            echo '<link rel="shortcut icon" href="' . $favicon['url'] . '" />';
        }

    }

    /**
     * Dynamically Adds the Facebook Pixel
     */
    public function doFacebookPixel()
    {

        if ( $fb_id = get_field('facebook_account_id', 'options') ) {
            
            global $post;
            
            $fb_standard_event = '';
            $value = '';
            
            if ( get_field('facebook_standard_event') != NULL && get_field('facebook_standard_event') == 'purchase' ) {
            
                if ( $value ) {
                    $fb_standard_event = 'fbq("track", "Purchase", {"value": "' . $value . '" , "currency" : "USD"});';
                } else {
                    $fb_standard_event = 'fbq("track", "Purchase");';
                }
        
            } elseif ( get_field('facebook_standard_event') != NULL ) {
                $fb_standard_event = get_field('facebook_standard_event');
            }
            
            ?>

            <!-- Facebook Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','https://connect.facebook.net/en_US/fbevents.js');
                fbq("init", "<?php echo $fb_id; ?>");
                fbq("track", "PageView");

                <?php echo $fb_standard_event; ?>

            </script>

            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_id; ?>&ev=PageView&noscript=1"/></noscript>
            <!-- End Facebook Pixel Code -->
        <?php } ?>
    <?php }

    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleTagManager()
    {
        ?>

        <?php if ( $gtm = get_field('google_tag_manager_id', 'options') ) { ?>

        <!-- Begin Google Tag Manager -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $gtm; ?>');
        </script>
        <!-- End Google Tag Manager -->

        <?php } ?>

    <?php }


    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleSiteVerification()
    {
        ?>

        <?php if ( $sv = get_field('google_site_verification_id', 'options') ) { ?>

        <!-- Begin Google Search Console Verification -->
        <meta name="google-site-verification" content="<?php echo $sv; ?>" />
        <!-- End Begin Google Search Console Verification -->

        <?php } ?>

    <?php }

    /**
     * Custom Head Scripts
     */
    public function CustomHeadScripts()
    {

        $custom_scripts = get_field('custom_tracking_scripts', 'options');
        
        if ( $custom_scripts ) {
            
            foreach ( $custom_scripts as $script ) {
            
                if ( $script['location'] == 'header' && $script['code'] != NULL ) {
                    echo $script['code'];
                }
            }
        }
    }

    /**
     * Custom Footer Scripts
     */
    public function customFooterScripts()
    {
        
        $custom_scripts = get_field('custom_tracking_scripts', 'options');
        
        if ( $custom_scripts ) {
           
            foreach ( $custom_scripts as $script ) {
            
                if ( $script['location'] == 'footer' && $script['code'] != NULL ) {
                    echo $script['code'];
                }
           
            }
        }
    }

    /**
     * Force Gravity Forms to init scripts in the footer and ensure that the DOM is loaded before scripts are executed
	 */
    public function footerScriptsInit()
    {
        return true;
    }

    /**
     * Wrap Gform - CData open
	 */
    public function wrapGformCdataOpen( $content = '' )
    {
        
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
        
        $content = 'document.addEventListener( "DOMContentLoaded", function() { ';
        
        return $content;
    
    }

    /**
     * Wrap Gform - CData close
	 */
    public function wrapGformCdataClose( $content = '' )
    {
   
        if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
            return $content;
        }
    
        $content = ' }, false );';
    
        return $content;
    
    }

    /**
     * Injects inline CSS into the head
	 */
    public function injectInlineCss()
    {

        global $post;
        $styles = array();

        if ( $global_styles = get_field('global_inline_styles', 'options') ) {
            $styles[] = $global_styles;
        }

        if ( $page_styles = get_field('page_inline_styles') ) {
            $styles[] = $page_styles;
        }

        foreach ( $styles as $style ) {
            $output .= $style;
        }
        
        if ( $output != '' ) {
            echo '<style id="inline-css">' . $output . '</style>';
        }

    }

    /**
     * Injects inline JS into the footer
	 */
    public function injectInlineJs()
    {
        global $post;

        if ( $page_script = get_field('page_inline_scripts') ) {
            echo '<script type="text/javascript" id="inline-js">' . $page_script . '</script>';
        }

    }

    /**
     * Conditionally shows message if URL contains ssmpb=save_reminder
     */
    public function saveReminderNotice()
    {

        if (isset($_GET["ssmpb"]) && trim($_GET["ssmpb"]) == 'save_reminder') {

            global $post;

            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>After you save this new ' . get_post_type() . ' item, you will need to reload the last page to retreive it.</p>';
            echo '</div>';
        }

    }

    /**
	 * Remove Admin Bar with custom Bar
	 *
	 */
	public function replaceAdminBar() {

		if ( is_user_logged_in() ) {

			global $post;
			$user = wp_get_current_user();
		
			if ( ! user_can( $user, 'edit_pages' ) ) {
			    return;
			}
		
			echo '<div class="ssm-admin-menu">';
                echo '<ul class="menu horizontal">';
                
                if ( defined( 'SSM_ENVIRONMENT' ) && $env = SSM_ENVIRONMENT ) {
                    echo '<li class="env env-' . sanitize_title_with_dashes( $env ) . '">' . ucfirst( $env ) . ' Environment</li>';
                }

			    echo '<li><a href="' . get_edit_post_link( $post->ID ) . '">Edit Page</a></li>';
			    echo '<li><a href="' . admin_url() . '">Admin Dashboard</a></li>';
			    echo '<li><a href="' . admin_url('admin.php?page=acf-options-brand-settings') . '">Brand Settings</a></li>';
			    echo '<li><a href="' . admin_url('admin.php?page=acf-options-documentation') . '">Documentation</a></li>';
		
			    if ( $user->user_login == 'jrstaatsiii') {
			        echo '<li><a href="' . admin_url('edit.php?post_type=acf-field-group') . '">Field Groups</a></li>';
			    }
		
			    echo '</ul>'; 
			echo '</div>';
		
		}
		
	}

}