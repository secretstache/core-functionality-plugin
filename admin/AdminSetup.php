<?php

namespace SSM\Admin;

use SSM\Admin\Admin;
use SSM\Includes\Helpers as SSMH;

class AdminSetup
{

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style( 'ssm', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueueScripts()
	{		

		wp_enqueue_script( 'ssm', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'ssm', 'login_logo', array('url' => SSMC_ADMIN_URL . 'images/login-logo.png' ) );
	
	}

    /**
	 * Remove unnecessary standard WP Roles	 
	 */
	public function removeRoles()
	{

		remove_role( 'subscriber' );
		remove_role( 'contributor' );
	
	}

	/**
	 * Remove default link for images 
	 */
	public function removeImageLink()
	{

		$image_set = get_option( 'image_default_link_type' );
		
		if ( $image_set !== 'none' ) {
			update_option('image_default_link_type', 'none');
		}

	}

	/**
	 * Show Kitchen Sink in WYSIWYG Editor by default
	 */
	public function showKitchenSink( $args )
	{
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}

	/**
	 * Disable unused widgets.
	 */
	public function removeWidgets()
	{

		unregister_widget( 'WP_Widget_Pages' );
		unregister_widget( 'WP_Widget_Calendar' );
		// unregister_widget( 'WP_Widget_Archives' );
		unregister_widget( 'WP_Widget_Meta' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_RSS' );
		unregister_widget( 'WP_Widget_Tag_Cloud' );
	
    }
    
    /**
	 * Add SSM widget to the dashboard.
	 */
	public function hostingDashboardWidget()
	{

		wp_add_dashboard_widget(
			'ssm_main_dashboard_widget', // Widget slug.
			'Managed Hosting by Secret Stache Media', // Title.
			array( $this, 'hostingWidgetFunction') // Display function.
		);  
	}
	
	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	public function hostingWidgetFunction()
	{
	
		$html = '<p>As a customer of our managed hosting service, you can rest assured that your software is kept up to date and served on the best hosting technology available.</p>';
		$html .= '<p>You are also covered by our <strong>Code Warantee</strong>, so if you see something that doesn\'t seem right, feel free to <a href="mailto:help@secretstache.com">reach out</a>.';
	
		echo $html;
	
	}

	/**
	 * Modifies the TinyMCE settings array
	 */
	public function updateTinyMCE( $init )
	{

		$init['block_formats'] = 'Paragraph=p;Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote';
		return $init;
	
	}

	/**
	 * Remove <p> tags from around images
	 * See: http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	 */
	public function removePtagsOnImages( $content )
	{
		return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	}

	/**
	 * Remove the injected styles for the [gallery] shortcode
	 
	 */
	public function removeGalleryStyles( $css )
	{
		return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	}

	/**
	* Set Home Page Programmatically if a Page Called "Home" Exists
	*/
	public function forceHomepage()
	{
	
		$homepage = get_page_by_title( 'Home' );
	
		if ( $homepage ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );
		}

	}

	/**
	* Removes unnecessary menu items from add new dropdown
	*/
	public function removeWPNodes()
	{
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_node( 'new-link' );
		$wp_admin_bar->remove_node( 'new-media' );
		$wp_admin_bar->remove_node( 'new-user' );
	}
	
	/**
	 * Filter Yoast SEO Metabox Priority
	 */
	public function yoastSeoMetaboxPriority()
	{
		return 'low';
	}

	/**
	 * Remove Editor Support on Pages (Replaced with SSMPB)
	 */
	public function removePostTypeSupport()
	{
		remove_post_type_support( 'page', 'editor' );
	}

	/**
	 * Remove default dasboards
	 */
	public function removeDashboardMeta()
	{

		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
		remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
		remove_meta_box( 'wpe_dify_news_feed', 'dashboard', 'normal' );
		remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
		remove_meta_box( 'ssm_main_dashboard_widget', 'dashboard', 'normal' );
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
	
	}

	/**
	 * Add SSM menu item
	 */
	public function createAdminMenu()
	{

		add_menu_page(
			__( 'Secret Stache', 'ssm' ), // page_title
			'Secret Stache', // menu_title
			'manage_options', // capability
			'ssm', // menu_slug
			'', // function
			'dashicons-layout', // icon
			5 // position
		);

	}

	/**
	 * Remove first submenu item
	 */
	public function removeMainSubMenu()
	{
		remove_submenu_page('ssm', 'ssm');
	}

	/**
	 * Create ACF Sub Pages and move them to SSM menu
	 */
	public function addAcfSubMenu()
	{

		if( class_exists('acf') ) {

			// Add Brand Settings Page
			acf_add_options_sub_page(array(
				'page_title' => 'Brand Settings',
				'menu_title' => 'Brand Settings',
				'parent_slug' => 'ssm',
			));

			// Add Documentation Page
			acf_add_options_sub_page(array(
				'page_title' => 'Documentation',
				'menu_title' => 'Documentation',
				'parent_slug' => 'ssm',
			));
		
		}

	}

	/**
	 * Move various menu items into LIB menu
	 */
	public function moveCptsToAdminMenu()
	{
	
		global $wp_post_types;
	
		if ( post_type_exists('insert_cpt') ) {
			$wp_post_types['insert_cpt']->show_in_menu = 'ssm';
		}
	
	}

	/**
	 * Filter the admin body classes if is_front
	 */
	public function isFrontAdminBodyClass( $classes ) 
	{
		
		global $post;
		
		if ( $post ) {
		
			$current_id = $post->ID;
			$front_page_id = get_option( 'page_on_front' );
		
			if ( $current_id == $front_page_id ) {
				return $classes = 'is-front';
			}
		
		}
	
	}

	/**
	 * Update width post meta on AJAX call
	 */
	public function updateWidthPostMeta( $post_id )
	{
	
		$column_values = array();
		
		foreach( $_POST as $key => $value) {
		
			if ( strpos( $key, 'columns_width') === 0 ) {
				array_push( $column_values, $value );
			}
	
		}
		
		if ( !empty( $column_values ) ) {
			for ( $i = 0; $i < count( $column_values ); $i++ ) {
		
				$key = 'custom_columns_width_' . $i;
			
				if ( get_post_meta( $post_id, $key, true ) ) {
					delete_post_meta( $post_id, $key );
				}
				add_post_meta( $post_id, $key, $column_values[$i] );
			
			}
		
		}
	}

	/**
	 * Get width values on AJAX call
	 */
	public function getWidthValues()
	{
		
		$response = array();
		
		for ( $i = 0; $i < $_POST['columns_count']; $i++ ) { 
			array_push( $response, get_post_meta( $_POST['page_id'], 'custom_columns_width_' . $i, true ) );
		}
		
		echo json_encode( $response );
		wp_die();
	
	}

    /**
	 * Makes the login screen's logo link to your homepage, instead of to WordPress.org 
	 */
	public function loginHeaderurl()
	{
		return home_url();
	}

	/**
	 * Makes the login screen's logo title attribute your site title, instead of 'WordPress'.
	 */
	public function loginHeadertitle()
	{
		return get_bloginfo( 'name' );
	}

	/**
	 * Replaces the login screen's WordPress logo with the 'login-logo.png' in your child theme images folder.
	 * Disabled by default. Make sure you have a login logo before using this function!
	 * Updated 2.0.1: Assumes SVG logo by default
	 */
	public function loginLogo()
	{

		$defaultLogo = SSMC_ADMIN_URL . 'images/login-logo.png';
		
		$background_image =  get_option('ssm_core_login_logo') != NULL ? get_option('ssm_core_login_logo') : $defaultLogo;
		$height =  get_option('ssm_core_login_logo_height') != NULL ? get_option('ssm_core_login_logo_height') : '128px';
		$width =  get_option('ssm_core_login_logo_width') != NULL ? get_option('ssm_core_login_logo_width') : '150px';
		
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
	 */
	public function mailFromName()
	{
		return get_option( 'blogname' );
	}

	/**
	 * Makes WordPress-generated emails appear 'from' your WordPress admin email address.
	 * Disabled by default, in case you don't want to reveal your admin email.
	 */
	public function wpMailFrom()
	{
		return get_option( 'admin_email' );
	}

	/**
	 * Removes the WP icon from the admin bar
	 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
	 */
	public function removeIconBar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	/**
	 * Modify the admin footer text
	 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
	 */
	public function adminFooterText()
	{

		$footer_text = get_option('ssm_core_agency_name') != NULL ? get_option('ssm_core_agency_name') : 'Secret Stache Media';
		$footer_link = get_option('ssm_core_agency_url') != NULL ? get_option('ssm_core_agency_url') : 'http://secretstache.com';

		echo 'Built by <a href="' . $footer_link . '" target="_blank">' . $footer_text . '</a> with WordPress.';
	}

	/**
	 * Inject internal WP JS variables on Core Settings page 
	 */
	public function	enqueueWpMedia() {
		
		if ( get_current_screen()->base == 'settings_page_ssm_core' ) {
 			wp_enqueue_media();
 		}
	
	}

	/**
 	 *  Show Environment in Admin Bar
 	 */
	public function addEnvNode( $wp_admin_bar ) {

		$env = '';
		
		if (defined('SSM_ENVIRONMENT')) {
		
			$env = SSM_ENVIRONMENT;
		
		}

		if ( $env == '' ) {
			return;
		}

		$args = array(
		
			'id'    => sanitize_title_with_dashes( $env ),
			'title' => ucfirst( $env ) . ' Environment',
			'meta'  => array( 'class' => 'env-' . sanitize_title_with_dashes( $env ) )
		
		);

		$wp_admin_bar->add_node( $args );

	}

	/**
	 * Remove Admin Bar on frontend
	 *
	 */
	public function removeAdminBar() {

		show_admin_bar(false);

	}

	/**
	 * Remove ACF Menu for non-checked users
	 *
	 */
	public function removeACFMenu() {

		$acfAdmins = get_option('ssm_core_acf_admin_users') != NULL ? get_option('ssm_core_acf_admin_users') : array(1);
	
		$current_user = wp_get_current_user();
	
		if ( $acfAdmins != NULL ) {

			if( !in_array( $current_user->ID, $acfAdmins ) ) {
				remove_menu_page('edit.php?post_type=acf-field-group');
			}
		
		}

	}

	/**
	 * Assign corresponding categories to ACF group fields
	 *
	 */
	public function assignCategoryGroupFields() {

		$posts = get_posts( array( 'post_type' => 'acf-field-group', 'posts_per_page' => -1, 'post_status' => array( 'publish', 'draft', 'acf-disabled', 'acf-' ) ) );

		foreach( $posts as $post ) {
			
			$post_id = $post->ID;
			$current_categories = wp_get_post_terms( $post_id, 'acf_category' );

			$category_name = str_replace( array("["), "", explode( "]", $post->post_title)[0] );
			$category = get_term_by( 'name', $category_name, 'acf_category' );

			if ( !in_array( $category, $current_categories ) ) {
				wp_set_object_terms( $post_id, array( $category->term_id ), 'acf_category' );
			}
		
		}

	}

}