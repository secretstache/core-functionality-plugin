<?php

class SSM_Public_Setup extends SSM_Public {

    /**
     * Show current year as a shortcode
	 * @since 1.0.0
	 */
    public function add_year_shortcode() {
        add_shortcode('year', array( $this, 'add_year_shortcode_cb' ) );
    }
    
    /**
     * add_year_shortcode() callback
	 * @since 1.0.0
	 */
    public function add_year_shortcode_cb() {
        
        $year = date('Y');
        return $year;
    
    }

    /**
     * Set Favicon
     *
	 * @since 1.0.0
     */
    public function set_favicon() {

        if ( $favicon = SSMH::get_field('favicon', 'options') ) {
            echo '<link rel="shortcut icon" href="' . $favicon['url'] . '" />';
        }

    }

    /**
     * Dynamically Adds the Facebook Pixel
     *
	 * @since 1.0.0
     */
    public function ssm_do_facebook_pixel() {

        if ( $fb_id = SSMH::get_field('facebook_account_id', 'options') ) {
            
            global $post;
            
            $fb_standard_event = '';
            $value = '';
            
            if ( SSMH::get_field('facebook_standard_event') != NULL && SSMH::get_field('facebook_standard_event') == 'purchase' ) {
            
                if ( $value ) {
                    $fb_standard_event = 'fbq("track", "Purchase", {"value": "' . $value . '" , "currency" : "USD"});';
                } else {
                    $fb_standard_event = 'fbq("track", "Purchase");';
                }
        
            } elseif ( SSMH::get_field('facebook_standard_event') != NULL ) {
                $fb_standard_event = SSMH::get_field('facebook_standard_event');
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
     *
	 * @since 1.0.0
     */
    public function ssm_setup_google_tag_manager() { ?>

        <?php if ( $gtm = SSMH::get_field('google_tag_manager_id', 'options') ) { ?>

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
     *
	 * @since 1.0.0
     */
    public function ssm_setup_google_site_verification() { ?>

        <?php if ( $sv = SSMH::get_field('google_site_verification_id', 'options') ) { ?>

        <!-- Begin Google Search Console Verification -->
        <meta name="google-site-verification" content="<?php echo $sv; ?>" />
        <!-- End Begin Google Search Console Verification -->

        <?php } ?>

    <?php }

    /**
     * Custom Head Scripts
     *
	 * @since 1.0.0
     */
    public function ssm_custom_head_scripts() {

        $custom_scripts = SSMH::get_field('custom_tracking_scripts', 'options');
        
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
     *
     * @since 1.0.0
     */
    public function ssm_custom_footer_scripts() {
        
        $custom_scripts = SSMH::get_field('custom_tracking_scripts', 'options');
        
        if ( $custom_scripts ) {
           
            foreach ( $custom_scripts as $script ) {
            
                if ( $script['location'] == 'footer' && $script['code'] != NULL ) {
                    echo $script['code'];
                }
           
            }
        }
    }

}