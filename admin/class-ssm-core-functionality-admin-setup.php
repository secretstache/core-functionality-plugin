<?php

class SSM_Core_Functionality_Admin_Setup extends SSM_Core_Functionality_Admin {

    /**
	 * Remove unnecessary standard WP Roles
	 * @since 1.0.0
	 */
    public function remove_roles() {

		remove_role( 'subscriber' );
		remove_role( 'contributor' );
	
	}

	/**
	 * Remove default link for images
	 * @since 1.0.0
	 */
	public function remove_image_link() {

		$image_set = get_option( 'image_default_link_type' );
		
		if ($image_set !== 'none') {
			update_option('image_default_link_type', 'none');
		}
	}

	/**
	 * Show Kitchen Sink in WYSIWYG Editor by default
	 * @since 1.0.0
	 */
	public function show_kitchen_sink( $args ) {
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}

	/**
	 * Disable unused widgets.
	 * @since 1.0.0
	 */
	public function remove_widgets() {

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
	public function hosting_dashboard_widget() {

		wp_add_dashboard_widget(
			'ssm_main_dashboard_widget', // Widget slug.
			'Managed Hosting by Secret Stache Media', // Title.
			array( $this, 'hosting_widget_function') // Display function.
		);  
	}
	
	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	public function hosting_widget_function() {
	
		$html = '<p>As a customer of our managed hosting service, you can rest assured that your software is kept up to date and served on the best hosting technology available.</p>';
		$html .= '<p>You are also covered by our <strong>Code Warantee</strong>, so if you see something that doesn\'t seem right, feel free to <a href="mailto:help@secretstache.com">reach out</a>.';
	
		echo $html;
	
	}

	/**
	 * Modifies the TinyMCE settings array
	 * @since 1.0.0
	 */
	public function update_tiny_mce( $init ) {

		$init['block_formats'] = 'Paragraph=p;Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote';
		return $init;
	
	}

	/**
	 * Remove <p> tags from around images
	 * See: http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	 * @since 1.0.0
	 */
	public function remove_ptags_on_images( $content ) {

		return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	
	}

	/**
	 * Remove the injected styles for the [gallery] shortcode
	 * @since 1.0.0
	 */
	public function remove_gallery_styles( $css ) {

		return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	
	}

	/**
	* Set Home Page Programmatically if a Page Called "Home" Exists
	* @since 1.0.0
	*/
	public function force_homepage() {
		$homepage = get_page_by_title( 'Home' );
	
		if ( $homepage ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );
		}
	}

	/**
	* Removes unnecessary menu items from add new dropdown
	* @since 1.0.0
	*/
	public function remove_wp_nodes() {
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_node( 'new-link' );
		$wp_admin_bar->remove_node( 'new-media' );
		$wp_admin_bar->remove_node( 'new-user' );
	}
	
	/**
	 * Filter Yoast SEO Metabox Priority
	 * @since 1.0.0
	 */
	public function yoast_seo_metabox_priority() {
		return 'low';
	}

}