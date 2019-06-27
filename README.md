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
	- git clone https://github.com/secretstache/stf-core-functionality
4. **Copy** all the content from one repository to another (including *.gitignore* excluding *.git/*)
	- Naming convention: *__project_code__-core-functionality* (*__stf__-core-functionality*)
6. **cd** to project’s folder.
7. **Rename** main plugin file from *core-functionality-plugin.php* to *__project_code__-core-functionality.php* (*__stf__-core-functionality.php*)
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
