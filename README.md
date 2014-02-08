# [Shoestrap](http://shoestrap.org): [Shoestrap](http://shoestrap.io) on steroids!

Shoestrap started as a fork of the amazing [Roots](http://roots.io) theme.
We added lots of hooks, actions, rewrote various parts of the theme and made it completely extensible.
You can use it as a standalone "core" theme and build on it using child themes or even plugins.
Using the included admin options developers and site owners can create and prototype sites without any coding and with just a few clicks.

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