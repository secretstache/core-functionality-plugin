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
