# Core Functionality Plugin

**Core Functionality Plugin** is essentially a core functionality boilerplate for SSM projects built on top of [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate). It contains a wide variety of tools that allow to implement custom *admin* and *public* experience, simplifies custom objects *registration process*, stores *ACF data* & injects it into *blade views*, registers useful *helpers* to use throughout the project, adds a lot of reusable *features* to work with internal WP entities and more.

## Main Concepts

- Built on top of [WP Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [OOP](https://en.wikipedia.org/wiki/Object-oriented_programming)-oriented
- Follows [PSR](https://www.php-fig.org/psr/) - PHP Standard Recommendation
- Follows [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) - Model-View-Controller pattern
- Contains *functional* layer of the project
- Implements [Local JSON](https://www.advancedcustomfields.com/resources/local-json/) feature
- Uses [Composer](https://getcomposer.org/) to manage dependencies
- Replaces [Sage Controllers hierarchy](https://github.com/roots/sage/tree/master/app/Controllers) in order to follow WP design pattern ( by separation functionality (CFP) and views (SST) )

## Installation

1. **Clone** both repositories (CFP and empty project repository) to */wp-content/plugins/*
	- git clone https://github.com/secretstache/core-functionality-plugin
	- git clone [repository_url]
4. **Copy** all the content from one repository to another (including *.gitignore* excluding *.git/*)
	- Naming convention: *__project_code__-core-functionality*
6. **cd** to project’s folder.
7. **Rename** main plugin file from *core-functionality-plugin.php* to *__project_code__-core-functionality.php*
8. **Run** *composer install*
9. **Activate** plugin in Admin Panel
10. **Install** and **activate** all recommended plugins

## Folders Walkthrough

**acf/**

- implementation of [Local JSON](https://www.advancedcustomfields.com/resources/local-json/) feature. Contains a list of .json config files that are bunched with corresponding ACF Field Groups. All the changes made in “Custom Fields” section in Admin Panel are reflected in .json files and vice-versa. Fields are automatically synced when updated version of plugin is pulled from repository.

**admin/**

- responsible for common admin scope functionality regardless of specific objects

	`Examples:` *createAdminMenu(), removeDashboardMetaBoxes(), addDevelopmentLinksWidget()*

**front/**

- responsible for common front scope functionality regardless of specific objects

	`Examples:` *replaceAdminBar(), customHeadScripts(), addYearShortcode()*

**includes/**

- contains a set of classes with additional functionality (walker helpers), core file and json config files

	`Examples:` *Activator.php, Helpers.php, Walker.php, json/acf_categories.json, json/objects/team.json*

**objects/**

- responsible for objects-specific functionality

	`Examples:` *Team.php, Clients.php, Projects.php*

**ssmpb/** (stands for SSM Page Builder)

- contains list of sage controllers

	`Examples:` *PageBuilder.php, FrontPage.php, Archive.php*


## How To

**Add function on plugin activation:**

- Go to *includes/Activator.php*
- Paste a function needed to run on plugin activation inside of *activate()* function.

	`Example:`
	```
	public static function activate()
	{
		add_role( 'stf_sales_rep', 'Sales Rep', array( 'read' => true ) );
	}
	```

**Add function on plugin deactivation:**

- Go to *includes/Deactivator.php*
- Paste a function needed to run on plugin deactivation inside of *deactivate()* function.

	`Example:`
	```
	public static function activate()
	{
		remove_role( 'stf_sales_rep' );
	}
	```

**Add new required plugin / remove unnecessary one:**

- Go to *includes/json/bundle.json*
- Add corresponding object to *bundle.json* config file

	`Example:`
	```
	…
	{
		"name": "User Switcher",
		"slug": "userswitcher",
		"required": false,
		"force_activation": false
	}
	…
	```

**Assign newly created or existing ACF Group to specific category:** (needs to be done each time before adding a group within UI)

- Go to *includes/json/acf_categories.json*
- Add corresponding line/object to *acf_categories.json* config file

	`Example:`
	```
	{
		"category_name": "Templates",
		"groups": [
			…
			"[Template] - Team Members"
			…
		]
	}
	```

**Add general admin hook:**

- Go to *includes/json/admin/admin-setup.json*
- Add corresponding declaration to *admin-setup.json* file.

	`Arguments:`

	- **“type”** : filter | action
	- **“name”** : hooked function
	- **“function”** : custom function
	- **“priority”** : (optional) default - 10
	- **“arguments”** : (optional) default - 1

- Go to *admin/AdminSetup.php*
- Add corresponding method to *AdminSetup* class

	`Example:`
	```
	…
	{
		"type": "action",
		"name": "wp_before_admin_bar_render",
		"function": "removeFromTopMenu",
		"priority": 999
	}
	…
	```

	```
	public function removeFromTopMenu()
	{

		global $wp_admin_bar;

		if ( is_admin() ) {
			…
			$wp_admin_bar->remove_node("wpseo-menu");
			…
		}

	}
	```

**Add general front hook:**

- Go to *includes/json/front/front-setup.json*
- Add corresponding declaration to *front-setup.json* file.

	`Arguments:`

	- **“type”** : filter | action
	- **“name”** : hooked function
	- **“function”** : custom function
	- **“priority”** : (optional) default - 10
	- **“arguments”** : (optional) default - 1

- Go to *admin/AdminSetup.php*
- Add corresponding method to *AdminSetup* class

	`Example:`
	```
	…
	{
		"type": "action",
		"name": "init",
		"function": "addYearShortcode"
	}
	…
	```
	```
	public function addYearShortcode()
	{
		add_shortcode("year", array( $this, "addYearShortcodeCB" ) );
	}
	```


**Register new object:** (CPT and taxonomy)

- Go to *includes/json/objects*
- Add corresponding *.json* file to the folder
- Add corresponding declarations to the created *.json* file.

- Go to *objects/*
- Add corresponding *class* to the folder
- Add corresponding *method* to the created class

	`Example:`

	**article.json**
	```
	{
		"name": "Article",
		"slug": "plugin_article",
		"class": "SSM\\Objects\\Article",
		"hooks": [
			{
				"type": "action",
				"name": "init",
				"function": "registerPostType"
			},
			{
				"type": "action",
				"name": "init",
				"function": "registerTaxonomies"
			}
		]
	}
	```

	**Article.php**
	```
	<?php

	namespace SSM\Objects;

	class Article
	{

		public function registerPostType() {

			register_extended_post_type(
				…
			)

		}

		public function registerTaxonomies() {

			register_extended_taxonomy(
				…
			)
		}
	}
	```

**Add object specific function:**

- Go to *includes/json/objects*
- Add corresponding declarations to the *.json* file.

- Go to *objects/*
- Add corresponding method to the *class*

	`Example:`
	```
	…

	'admin_cols'    => array(

		'featured_image' => array(
			'title'          => 'Featured Image',
			'featured_image' => 'thumbnail'
		),
		'title',
		'custom_title' => array(
			'title'     => "Title",
			'meta_key'  => 'job_title'
		)
	)
	…
	```

	```
	…

	public function moveThumbnailColumn( $columns ) {

		unset($columns['title']);

		$new_columns = array_slice($columns, 0, 2, true) + array("title" => "Name") + array_slice($columns, 2, count($columns) - 1, true);

		return $new_columns;

	}
	…
	```

**Add new helper function:**

- Go to *includes/Helpers.php*
- Add corresponding *static* method to *Helpers* class

	`Example:`
	```
	public static function isSSM( $user_id )
	{

	$members = get_option("ssm_core_team_members") ? get_option("ssm_core_team_members") : array();

		return ( in_array( $user_id, $members ) ) ? true : false;

	}
	```

**Extend Walker class or add any conditions:**

- Go to *includes/Walker.php*
- Add changes to corresponding *method*

	`Example:`
	```
	…
	if( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
				$class_names .= " active";
	}
	…
	```

**Add common LB function:**

- Go to *ssmpb/PageBuilder.php* (parent class).
- Add corresponding *static* method. Use the function in the theme.

	`Example:`
	```
	public static function getAddress( $address )
	{

		$response = "";
		$street1 = $address->street1;
		…
		return $response;
	}
	```
	```
	$builder->getAddress( $address_object )
	```

**Add specific LB function:**

- Go to *ssmpb/FrontPage.php* (inherited class).
- Add corresponding *static* method. Use the function in the theme.

	`Example:`
	```
	public static function getFrontPageData( $page_id )
	{

		$response = "";
		$data = get_some_data( $page_id );
		…
		return $response;
	}
	```
	```
	$builder->getFrontPageData( get_the_ID() )
	```

**Add 3rd party plugin dependency:**

- Go to *composer.json*
- Add corresponding dependency in *“require”* section.
- Run *composer update*

	`Example:`
	```
	"require": {
		…
		"soberwp/bundle": "1.0.2-p"
		…
	}
	```
