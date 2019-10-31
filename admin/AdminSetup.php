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
		wp_enqueue_style( "ssm", plugin_dir_url( __FILE__ ) . "css/admin.css", array(), '1.0', "all" );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueueScripts()
	{

		wp_enqueue_script( "ssm", plugin_dir_url( __FILE__ ) . "js/admin.js", array( "jquery" ), '1.0', false );

		wp_localize_script( "ssm", "custom", array( "ajax_url" => admin_url( "admin-ajax.php" )));
		wp_localize_script( "ssm", "login_logo", array("url" => SSMC_URL . "admin/" . "images/login-logo.png" ) );

	}

    /**
	 * Remove unnecessary standard WP Roles
	 */
	public function removeRoles()
	{

		remove_role( "subscriber" );
		remove_role( "contributor" );

	}

	/**
	 * Remove default link for images
	 */
	public function removeImageLink()
	{

		$image_set = get_option( "image_default_link_type" );

		if ( $image_set !== "none" ) {
			update_option("image_default_link_type", "none");
		}

	}

	/**
	 * Show Kitchen Sink in WYSIWYG Editor by default
	 */
	public function showKitchenSink( $args )
	{
		$args["wordpress_adv_hidden"] = false;
		return $args;
	}

	/**
	 * Disable unused widgets.
	 */
	public function removeWidgets()
	{

		unregister_widget( "WP_Widget_Pages" );
		unregister_widget( "WP_Widget_Calendar" );
		unregister_widget( "WP_Widget_Meta" );
		unregister_widget( "WP_Widget_Recent_Posts" );
		unregister_widget( "WP_Widget_Recent_Comments" );
		unregister_widget( "WP_Widget_RSS" );
		unregister_widget( "WP_Widget_Tag_Cloud" );

    }

	/**
	 * Modifies the TinyMCE settings array
	 */
	public function updateTinyMCE( $init )
	{

		$init["block_formats"] = "Paragraph=p;Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote";
		return $init;

	}

	/**
	 * Remove <p> tags from around images
	 * See: http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	 */
	public function removePtagsOnImages( $content )
	{
		return preg_replace( "/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU", "\1\2\3", $content );
	}

	/**
	 * Remove the injected styles for the [gallery] shortcode

	 */
	public function removeGalleryStyles( $css )
	{
		return preg_replace( "!<style type=\"text/css\">(.*?)</style>!s", "", $css );
	}

	/**
	* Set Home Page Programmatically if a Page Called "Home" Exists
	*/
	public function forceHomepage()
	{

		$homepage = get_page_by_title( "Home" );

		if ( $homepage ) {
			update_option( "page_on_front", $homepage->ID );
			update_option( "show_on_front", "page" );
		}

	}

	/**
	* Removes unnecessary menu items from add new dropdown
	*/
	public function removeWPNodes()
	{
		global $wp_admin_bar;

		$wp_admin_bar->remove_node( "new-link" );
		$wp_admin_bar->remove_node( "new-media" );
		$wp_admin_bar->remove_node( "new-user" );
	}

	/**
	 * Filter Yoast SEO Metabox Priority
	 */
	public function yoastSeoMetaboxPriority()
	{
		return "low";
	}

	/**
	 * Remove Editor Support on Pages (Replaced with SSMPB)
	 */
	public function removePostTypeSupport()
	{
		remove_post_type_support( "page", "editor" );
		remove_post_type_support( "page", "thumbnail" );
	}

	/**
	 * Remove default dasboards
	 */
	public function removeDashboardMeta()
	{

		remove_meta_box( "dashboard_right_now", "dashboard", "normal" );
		remove_meta_box( "dashboard_incoming_links", "dashboard", "normal" );
		remove_meta_box( "dashboard_plugins", "dashboard", "normal" );
		remove_meta_box( "dashboard_primary", "dashboard", "side" );
		remove_meta_box( "dashboard_secondary", "dashboard", "normal" );
		remove_meta_box( "dashboard_quick_press", "dashboard", "side" );
		remove_meta_box( "dashboard_recent_drafts", "dashboard", "side" );
		remove_meta_box( "dashboard_recent_comments", "dashboard", "normal" );
		remove_meta_box( "dashboard_activity", "dashboard", "normal");
		remove_meta_box( "rg_forms_dashboard", "dashboard", "normal" );
		remove_meta_box( "wpe_dify_news_feed", "dashboard", "normal" );
		remove_meta_box( "wpseo-dashboard-overview", "dashboard", "normal" );
		remove_meta_box( "ssm_main_dashboard_widget", "dashboard", "normal" );
		remove_action( "try_gutenberg_panel", "wp_try_gutenberg_panel" );

	}

	/**
	 * Always show Welcome metabox by default for new users
	 */
	public function showWelcomeMetabox( $user_id ) {

		if ( 1 != get_user_meta( $user_id, 'show_welcome_panel', true ) )
			update_user_meta( $user_id, 'show_welcome_panel', 1 );

	}

	/**
	 * Add SSM menu item
	 */
	public function createAdminMenu()
	{

		add_menu_page(
			__( "Secret Stache", "ssm" ), // page_title
			"Secret Stache", // menu_title
			"manage_options", // capability
			"ssm", // menu_slug
			"", // function
			"dashicons-layout", // icon
			5 // position
		);

	}

	/**
	 * Remove first submenu item
	 */
	public function removeMainSubMenu()
	{
		remove_submenu_page("ssm", "ssm");
	}

	/**
	 * Create ACF Sub Pages and move them to SSM menu
	 */
	public function addAcfSubMenu()
	{

		if( class_exists("acf") ) {

			// Add Brand Settings Page
			acf_add_options_sub_page(array(
				"page_title" => "Brand Settings",
				"menu_title" => "Brand Settings",
				"parent_slug" => "ssm",
			));

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
			$front_page_id = get_option( "page_on_front" );

			if ( $current_id == $front_page_id ) {
				return $classes = "is-front";
			}

		}

	}

	/**
	 * Get width values on AJAX call
	 */
	public function getWidthValues()
	{

		$response = array();

		for ( $i = 0; $i < $_POST["columns_count"]; $i++ ) {
			array_push( $response, get_post_meta( $_POST["page_id"], "custom_columns_width_" . $i, true ) );
		}

		echo json_encode( $response );
		wp_die();

	}

	/**
	 * Update width post meta on AJAX call
	 */
	public function updateWidthPostMeta( $post_id )
	{

		$column_values = array();

		foreach( $_POST as $key => $value) {

			if ( strpos( $key, "columns_width") === 0 ) {
				array_push( $column_values, $value );
			}

		}

		if ( !empty( $column_values ) ) {
			for ( $i = 0; $i < count( $column_values ); $i++ ) {

				$key = "custom_columns_width_" . $i;

				if ( get_post_meta( $post_id, $key, true ) ) {
					delete_post_meta( $post_id, $key );
				}
				add_post_meta( $post_id, $key, $column_values[$i] );

			}

		}
	}

    /**
	 * Makes the login screen"s logo link to your homepage, instead of to WordPress.org
	 */
	public function loginHeaderUrl()
	{
		return home_url();
	}

	/**
	 * Makes the login screen"s logo title attribute your site title, instead of "Powered by WordPress".
	 */
	public function loginHeaderText()
	{
		return get_bloginfo( "name" );
	}

	/**
	 * Replaces the login screen"s WordPress logo with the "login-logo.png" in your child theme images folder.
	 * Disabled by default. Make sure you have a login logo before using this function!
	 * Updated 2.0.1: Assumes SVG logo by default
	 */
	public function loginLogo()
	{
		$defaultLogo = SSMC_URL . "admin/" . "images/login-logo.png";
		$background_image =  get_option("ssm_core_login_logo") != NULL ? get_option("ssm_core_login_logo") : $defaultLogo;
        ?>

		<?php if (!is_user_logged_in() && $GLOBALS["pagenow"] === "wp-login.php" ):  ?>

			<style type="text/css">
				body.login div#login h1 a {
					background-image: url(<?php echo $background_image; ?>) !important;
					background-repeat: no-repeat;
					background-size: contain;
					width: auto;
					height: 90px;
					margin-bottom: 15px;
				}
			</style>

		<?php endif; ?>

        <?php
    }

	/**
	 * Makes WordPress-generated emails appear "from" your WordPress site name, instead of from "WordPress".
	 */
	public function mailFromName()
	{
		return get_option( "blogname" );
	}

	/**
	 * Makes WordPress-generated emails appear "from" your WordPress admin email address.
	 * Disabled by default, in case you don"t want to reveal your admin email.
	 */
	public function wpMailFrom()
	{
		return get_option( "admin_email" );
	}

	/**
	 * Removes the WP icon from the admin bar
	 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
	 */
	public function removeIconBar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu("wp-logo");
	}

	/**
	 * Modify the admin footer text
	 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
	 */
	public function adminFooterText()
	{

		$footer_text = get_option("ssm_core_agency_name") != NULL ? get_option("ssm_core_agency_name") : "Secret Stache Media";
		$footer_link = get_option("ssm_core_agency_url") != NULL ? get_option("ssm_core_agency_url") : "http://secretstache.com";

		echo "Built by <a href=\"" . $footer_link . "\" target=\"_blank\">" . $footer_text . "</a> with WordPress.";
	}

	/**
	 * Inject internal WP JS variables on Core Settings page
	 */
	public function	enqueueWpMedia() {

		if ( get_current_screen()->base == "settings_page_ssm_core" ) {
 			wp_enqueue_media();
 		}

	}

	/**
 	 *  Show Environment in Admin Bar
 	 */
	public function addEnvNode( $wp_admin_bar ) {

		$env = "";

		if (defined("SSM_ENVIRONMENT")) {

			$env = SSM_ENVIRONMENT;

		}

		if ( $env == "" ) {
			return;
		}

		$args = array(

			"id"    => sanitize_title_with_dashes( $env ),
			"title" => ucfirst( $env ) . " Environment",
			"meta"  => array( "class" => "env-" . sanitize_title_with_dashes( $env ) )

		);

		$wp_admin_bar->add_node( $args );

	}

	/**
	 * Remove ACF Menu for non-checked users
	 *
	 */
	public function removeACFMenu() {

		$acfAdmins = get_option("ssm_core_acf_admin_users") != NULL ? get_option("ssm_core_acf_admin_users") : array(1);

		$current_user = wp_get_current_user();

		if ( $acfAdmins != NULL ) {

			if( !in_array( $current_user->ID, $acfAdmins ) ) {

				remove_menu_page("edit.php?post_type=acf-field-group");

				# Check current admin page.
				if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'acf-field-group' ){

					wp_redirect( admin_url( '/' ), 301 );
					exit;

				}

			}

		}

	}

	/**
	 * Assign corresponding categories to ACF group fields
	 *
	 */
	public function assignCategoryGroupFields() {

		if ( count( scandir( SSMC_DIR . "includes/json/") ) > 5 ) {

			$items = json_decode( file_get_contents( SSMC_DIR . "includes/json/acf_categories.json" ), true );

			foreach( $items as $item ) {

				$category_name = $item["category_name"];
				$groups = $item["groups"];

				$category = get_term_by( "name", $category_name, "acf_category" );

				foreach( $groups as $group_name ) {

					$group = get_page_by_title( $group_name, OBJECT, "acf-field-group" );

					if( $group ) {

						$current_categories = wp_get_post_terms( $group->ID, "acf_category" );

						if ( empty( $current_categories ) ) {

							wp_set_object_terms( $group->ID, array( $category->term_id ), "acf_category" );

						}

					}

				}

			}


		}

	}

	/**
	 * Dynamically Update The Flexible Content Label
	 */
	public function updateACFSectionTitle( $title, $field, $layout, $i ) {

		if ( get_sub_field("option_section_label") ) {

			$label = get_sub_field("option_section_label");

		} else {

			$label = $title;

		}

		return $label;

	}

	/**
	 * Dynamically prepend "Inactive" to the template title
	 */
	public function prependACFInactiveTitle( $title, $field, $layout, $i ) {

		if ( get_sub_field("option_status" ) == false ) {

			$label = "<span class=\"template-inactive\">Inactive</span> - " . $title;

		} else {

			$label = $title;

		}

		return $label;

	}

	/**
	 * Collapse Flexible Content Fields by default
	 */
	public function flexibleACFContentCollapse() {
		?>

		<style id="acf-flexible-content-collapse">.acf-flexible-content .acf-fields { display: none; }</style>

		<script type="text/javascript">

			jQuery(function($) {
					$(".acf-flexible-content .layout").addClass("-collapsed");
					$("#acf-flexible-content-collapse").detach();
			});

		</script>

		<?php
	}

	/**
	 * Create admin users on project setup
	 */
	public function createAdminUsers() {

		if ( !username_exists( "alex" ) ) {

			$alex_pass = wp_generate_password();
			$alex_id = wp_create_user( "alex", $alex_pass, "alex@secretstache.com" );

			if ( $alex_id ) {

				add_option( "alex_pass", $alex_pass );

				$alex = new \WP_User( $alex_id );
				$alex->remove_role( "subscriber" );
				$alex->add_role( "administrator" );

			}

		}

		if ( !username_exists( "jrstaatsiii" ) ) {

			$rich_pass = wp_generate_password();
			$rich_id = wp_create_user( "jrstaatsiii", $rich_pass, "rich@secretstache.com" );

			if ( $rich_id ) {

				add_option( "rich_pass", $rich_pass );

				$rich = new \WP_User( $rich_id );
				$rich->remove_role( "subscriber" );
				$rich->add_role( "administrator" );

			}

		}

	}

	/**
	 * Fires when clicked "remove" button in Admin Credentials section
	 */
	public function removeFromAdmins()
	{

		$action = $_POST["custom_action"];
		$data = $_POST["custom_value"];

		if ( $action == "remove-user" ) {
			$response = wp_delete_user( username_exists("admin"), $data );
		} elseif ( $action == "remove-option" ) {
			$response = delete_option( $data );
		}

		echo json_encode( $response );
		wp_die();

	}

	/**
	 * Fires when clicked 'Send Email' button in Admin Credentials section
	 */
	public function sendAdminEmail()
	{

		$email_address = $_POST['email_address'];
		$password = $_POST['password'];
		$username = $_POST['username'];

		if( $email_address && $password && $username ) {

			$subject = "[" . get_bloginfo('name') . "] Login Details";
			$message = "\r\nUsername: " . $username . "\r\nPassword: " . $password . "\r\n\r\nTo login, visit the following address:\r\n" . admin_url();

			$response = wp_mail( $email_address, $subject, $message );

		} else {
			$response = false;
		}

		echo json_encode( $response );
		wp_die();

	}

	public function registerACFCategoryTaxonomy() {

		register_extended_taxonomy( "acf_category", "acf-field-group", array(

			"hierarchical"		=> false

		), array(

			"singular"  		=> "Category",
			"plural"    		=> "Categories",
			"slug"      		=> "category"

		) );

	}

	public function registerACFTerms() {

        wp_insert_term("Modules", "acf_category");
        wp_insert_term("Lists", "acf_category");
        wp_insert_term("Components", "acf_category");
        wp_insert_term("Options", "acf_category");
        wp_insert_term("Module Lists", "acf_category");
        wp_insert_term("Templates", "acf_category");
        wp_insert_term("Page UI", "acf_category");
        wp_insert_term("Settings Page UI", "acf_category");

	}

	/**
	 * Remove unnecessary items from Top Menu
	 *
	 */
	function removeFromTopMenu( ) {

		global $wp_admin_bar;

		if (is_admin()) {

			$wp_admin_bar->remove_node("wpseo-menu");
			$wp_admin_bar->remove_node("autoptimize");
			$wp_admin_bar->remove_node("archive");
			$wp_admin_bar->remove_node("updates");
			$wp_admin_bar->remove_node("gform-forms");
			$wp_admin_bar->remove_node("searchwp");
			$wp_admin_bar->remove_node("comments");

		}
	}

	function addDevelopmentLinksWidget() {

		$current_user = wp_get_current_user();

		if( SSMH::isSSM( $current_user->data->ID ) ) {

			wp_add_dashboard_widget('development_links', 'Development Links', array( $this, 'addDevelopmentLinksWidgetCB'));

		}

	}

	function addDevelopmentLinksWidgetCB( $post, $callback_args ) {

		$response = "";

		$response .= "<a href=\"" . admin_url('plugins.php') . "\">Plugins</a>";
		$response .= " | " . "<a href=\"" . admin_url('options-general.php?page=ssm_core') . "\">Core Settings</a>";
		$response .= " | " . "<a href=\"" . admin_url('options-general.php?page=menu_editor') . "\">Menu Editor Pro</a>";
		$response .= " | " . "<a href=\"" . admin_url('tools.php?page=wp-migrate-db-pro') . "\">Migrate DB Pro</a>";

		$acfAdmins = get_option("ssm_core_acf_admin_users") ? get_option("ssm_core_acf_admin_users") : array(1);

		$current_user = wp_get_current_user();

		if ( $acfAdmins != NULL ) {

			if( in_array( $current_user->ID, $acfAdmins ) ) {

				$response .= " | " . "<a href=\"" . admin_url('edit.php?post_type=acf-field-group') . "\">Custom Fields</a>";

			}

		}

		echo $response;
	}

	function hideProductionACF() {

		# Check SSM Environment.
		if ( defined( "SSM_ENVIRONMENT" ) && ( $env = SSM_ENVIRONMENT ) && ( $env == 'production' ) ) : ?>

			<style type="text/css">

				.post-type-acf-field-group ul.subsubsub {
					display: none;
				}

			</style>

		<?php endif;

	}

	/**
	 * Assign custom Page Post States
	 */
	public function addAdminPagesPostStates( $post_states, $post ) {

		if( get_page_template_slug( $post ) == 'views/template-landing-page.blade.php' ) {
			$post_states[] = 'Landing Page';
		}

		return $post_states;

	}

}
