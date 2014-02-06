# [Shoestrap](http://shoestrap.org): [Shoestrap](http://shoestrap.io) on steroids!

We Forked the Shoestrap theme and added lots of options, hooks and actions in there, making it a theme that anyone can use and customize their site to a great extent, making it their own. Our goal was to make it easier for developers to create and prototype sites without any coding and with just a few clicks.

## Options Panel

We have included the [ReduxFramework](http://reduxframework.com) into Shoestrap so you can easily customize all the aspects of this theme (props @[@dovy](https://twitter.com/simplerain)).

## Automated Compiling

Using the Options Panel, users can change most of the variables available on the core of Bootstrap 3, and the [php.less](http://lessphp.gpeasy.com/) compiler generates the theme CSS for on the fly.
We tried to keep the number of options to a minimum (there are still more than 150 settings available) so we customized the variables compiling and most variables interact with each-other to generate the values of the variables that we did not include. This way you can focus on what's important and be sure that your site will look great.
We also made sure that the compiler is multisite-compatible, since each site has its own stylesheet in a network, with the site ID appended to it.

But that's not all, we also added many options to change the layout of your site, add a 2nd sidebar, add logos, background images, patterns and more.

## Widget Areas

We included a number of widget areas in the shoestrap theme. These include:
* Primary Sidebar
* Secondary Sidebar
* Jumbotron
* Header Area
* 4 Footer Widget Areas
* 5 Slide-Down Widget areas (sliding-down from the pripary navbar)

## Developer-friendly

Developers will also find this theme easy to use and develop on, since we made it completely modular and easy extendable. 
* Everything on this theme is a module (including the compiler). All you have to do to create a new module is create a new folder in `/lib/modules/` and add a `module.php` file in there. The file will automatically be detected and included, you don't have to do anything more than that.
* Using our hooks and actions, instead of hacking the theme files developers can simply create a plugin and override the default templates and template parts, or inject their custom content wherever they want.

#### Example:
Want to override the template for single posts? No problem! All you have to do is add this to your custom plugin:

```php
function my_custom_single_content() {
	// CONTENT HERE
}
add_action( 'shoestrap_content_single_override', 'my_custom_single_content' );
```

## Contributing

Everyone is welcome to help [contribute](CONTRIBUTING.md) and improve this project. There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/shoestrap/shoestrap/issues)
* Replying to questions on the [forum](http://shoestrap.org/forums/forum/shoestrap/)

## Support

Use the [Shoestrap Forums](http://shoestrap.org/forums/forum/shoestrap/) to ask questions and get support.


# [Shoestrap Theme](http://shoestrap.io/)

[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)

Shoestrap is a WordPress starter theme based on [HTML5 Boilerplate](http://html5boilerplate.com/) & [Bootstrap](http://getbootstrap.com/) that will help you make better themes.

* Source: [https://github.com/shoestrap/shoestrap](https://github.com/shoestrap/shoestrap)
* Home Page: [http://shoestrap.io/](http://shoestrap.io/)
* Twitter: [@retlehs](https://twitter.com/retlehs)
* Newsletter: [Subscribe](http://shoestrap.io/subscribe/)
* Forum: [http://discourse.shoestrap.io/](http://discourse.shoestrap.io/)

## Installation

Clone the git repo - `git clone git://github.com/shoestrap/shoestrap.git` - or [download it](https://github.com/shoestrap/shoestrap/zipball/master) and then rename the directory to the name of your theme or website. [Install Grunt](http://gruntjs.com/getting-started), and then install the dependencies for Shoestrap contained in `package.json` by running the following from the Shoestrap theme directory:

```
npm install
```

Reference the [theme activation](http://shoestrap.io/shoestrap-101/#theme-activation) documentation to understand everything that happens once you activate Shoestrap.

## Theme Development

After you've installed Grunt and ran `npm install` from the theme root, use `grunt watch` to watch for updates to your LESS and JS files and Grunt will automatically re-build as you write your code.

## Configuration

Edit `lib/config.php` to enable or disable support for various theme functions and to define constants that are used throughout the theme.

Edit `lib/init.php` to setup custom navigation menus and post thumbnail sizes.

## Documentation

### [Shoestrap Docs](http://shoestrap.io/docs/)

* [Shoestrap 101](http://shoestrap.io/shoestrap-101/) — A guide to installing Shoestrap, the files and theme organization
* [Theme Wrapper](http://shoestrap.io/an-introduction-to-the-shoestrap-theme-wrapper/) — Learn all about the theme wrapper
* [Build Script](http://shoestrap.io/using-grunt-for-wordpress-theme-development/) — A look into the Shoestrap build script powered by Grunt
* [Shoestrap Sidebar](http://shoestrap.io/the-shoestrap-sidebar/) — Understand how to display or hide the sidebar in Shoestrap

## Features

* Organized file and template structure
* HTML5 Boilerplate's markup along with ARIA roles and microformat
* Bootstrap
* [Grunt build script](http://shoestrap.io/using-grunt-for-wordpress-theme-development/)
* [Theme activation](http://shoestrap.io/shoestrap-101/#theme-activation)
* [Theme wrapper](http://shoestrap.io/an-introduction-to-the-shoestrap-theme-wrapper/)
* Root relative URLs
* Cleaner HTML output of navigation menus
* Cleaner output of `wp_head` and enqueued scripts/styles
* Nice search (`/search/query/`)
* Image captions use `<figure>` and `<figcaption>`
* Example vCard widget
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* [Multilingual ready](http://shoestrap.io/wpml/) (Brazilian Portuguese, Bulgarian, Catalan, Danish, Dutch, English, Finnish, French, German, Hungarian, Indonesian, Italian, Korean, Macedonian, Norwegian, Polish, Russian, Simplified Chinese, Spanish, Swedish, Traditional Chinese, Turkish, Vietnamese, Serbian)

## Contributing

Everyone is welcome to help [contribute](CONTRIBUTING.md) and improve this project. There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/shoestrap/shoestrap/issues)
* Replying to questions on the [forum](http://discourse.shoestrap.io/)

## Support

Use the [Shoestrap Discourse](http://discourse.shoestrap.io/) to ask questions and get support.

[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/28b8970195f0fd45e6cbce37fd65c7e2 "githalytics.com")](http://githalytics.com/shoestrap/shoestrap)
