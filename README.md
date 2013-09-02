# [Shoestrap](http://shoestrap.org): [Roots](http://roots.io) on steroids!

We Forked the Roots theme and added lots of options, hooks and actions in there, making it a theme that anyone can use and customize their site to a great extent, making it their own. Our goal was to make it easier for developers to create and prototype sites without any coding and with just a few clicks.

## Options Panel

We originally intended to include [SMOF](https://github.com/syamilmj/Options-Framework) as an options panel, but due to various issues and bugs that SMOF has, we instead chose to develop our own Options Framework. So [@dovy](https://twitter.com/simplerain), a shoestrap contributor built and integrated [SimpleOptions](https://github.com/SimpleRain/SimpleOptions) into Shoestrap.

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

There are dozens of actions available, so before hacking the theme to do what you want, don't forget to check if you can do it with the existing actions.

## Available Options:

* Branding:
  When it comes to branding, users can choose the colors of their site, as well as upload a logo, a favicon or even an icon that is used on apple devices.

* Layout:
  In the Layout section of the options, users can choose a site style ( Wide (normal) / Boxed / Fluid ), whether they will have 1 or 2 sidebars, their width and where they will be located (left, right, one left and the other right etc).
  But that’s not all.. there are some advanced layout options as well, including the breakpoints of the responsive layout, defining the width of the site, adding breadcrumbs, custom margins and more.

* Background:
  We tried to incude every option we could think of for the background of your site… So we added options for background color + background opacity of the content area, background images, fixed or scrolling backgrounds, full width or custom positioning of the background image, as well as background patterns.
  We even included some sample patterns from [SubtlePatterns](http://subtlepatterns.com/) (licensed under a Creative Commons Attribution-ShareAlike 3.0 License).

* Header:
  Now this is where things start getting interesting…The header section of the settings actually includes 3 sections of a site:
    * Primary NavBar
    * Header
    * Secondary NavBar
  
  For the Primary and secondary navbars you can choose background color, background opacity, whether you want to display the sitename or logo, the positioning of he navbar (scolling, fixed to the top or fixed to the bottom), its height, the typography that will be used for menus in the navbar, the typography that will be used for branding (if you’ve chosen to display the sitename there), whether you want a searchform or social links in the navbar an some other minor options.
  The “Header” is an extra area that can be optionally added right below the navbar that can be used to display your logo (in case you don’t want it displayed in the navbar, you can add it here).

* Jumbotron:
  The Jumbotron section of your site can be used to display your content in a prominent position.
  You can change the background of your Jumbotron (yes, we included all the options available for the general background here too), change the typography of header elements as well as general text, and many more options too.

* Blog Options:
  In the "Blog" section, we added some general options that affect your site in various ways. You can choose to display your widgets as [wells](http://getbootstrap.com/components/#wells) or [panels](http://getbootstrap.com/components/#panels), define the post excerpt length, add featured images on individual posts and archives and set their sizes. Featured images are resized using the [Wordpress Timthumb Alternative](http://matthewruddy.github.io/Wordpress-Timthumb-alternative/) which also supports retina images.

* Typography:
  Using the typography options you can change the default font family, font size and color for all text on your site, or your header elements individually.
  We also included **[all google webfonts](http://www.google.com/fonts/)**

* Social Sharing:
  You can make it easier for your visitors to share your content using the included, script-less sharing buttons that we included. Just enable the social networks that interest you and we'll take care of the rest.

* Social Links:
  You can enter your social network URLs in this section and we will add the appropriate icons to your navbar or your footer, making it easier for your clients to follow you in all the social networks that you use.

* Footer:
  You can select the background color, opacity, footer text and more.

* Advanced:
  The advanced section includes general options such as the base padding, base borer radius and more. In this section users can also add their Google Analytics ID, turn on or off URL rewrites, Root Relative URLs, Nice Search, Add custom CSS and custom JS, toggle the adminbar on/off and some other goodies as well.

* Licencing:
  The licencing section allows users to enter their licence ID (sent via email to users when the theme is downloaded from [shoestrap.org](http://shoestrap.org) - free). All that this licence does is enable us to notify users of any theme updates and enable WordPress updates to work on the theme without the need for manual updates.
  Developers can also add any licencing fields that they include in their addons here so that they don't have to create a new page in the admin section for that.


Overall, Shoestrap combines all the good elements of Roots with features that are usually only seen in expensive, premium themes and frameworks! You can help its development by filing bug reports on the [issues list](https://github.com/shoestrap/shoestrap/issues) on github, or even better by [forking it](https://github.com/shoestrap/shoestrap) and submitting your own patches!

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
