# [Shoestrap](http://shoestrap.org): [Roots](http://roots.io) on steroids!

We Forked the Roots theme and added lots of options, hooks and actions in there, making it a theme that anyone can use and customize their site to a great extent, making it their own. Our goal was to make it easier for developers to create and prototype sites without any coding and with just a few clicks.

## Options Panel

We originally intended to include [SMOF](https://github.com/syamilmj/Options-Framework) as an options panel, but due to various issues and bugs that SMOF has, we instead chose to develop our own Options Framework. So [@dovy](https://twitter.com/simplerain), a shoestrap contributor, built and integrated [ReduxFramework](http://reduxframework.com) into Shoestrap. 

## Automated Compiling

Using the Options Panel, users can change most of the variables available on the core of Bootstrap 3, and the [lessphp](http://leafo.net/lessphp/) compiler generates and -optionally- minifies the CSS for the theme on the fly. We tried to keep the number of options to a minimum (there are still more than 80 settings available) so we customized the variables compiling and most variables interact with each-other to generate the values of the variables that we did not include. This way you can focus on what's important and be sure that your site will look great.
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

[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/28b8970195f0fd45e6cbce37fd65c7e2 "githalytics.com")](http://githalytics.com/shoestrap/shoestrap)
