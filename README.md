# [Shoestrap](https://github.com/aristath/shoestrap)

Shoestrap is a WordPress theme that’s based on [HTML5 Boilerplate](http://html5boilerplate.com/) and [Bootstrap from Twitter](http://twitter.github.com/bootstrap/).
The logic behind it and a big part of it's code was inspired from the amazing [Roots theme](http://rootstheme.com ).
It is built in a modular way, allowing developers to properly organize their code and templates.

### Caution to themers:
Before changing your theme's css, we suggest that you first use the customizer to apply any colors etc.
If you find that you need something more, then DO NOT edit the assets/css/app.css file.
This theme includes a PHP-Less compiler, so you can use lesscss styles in your theme and take full advantage of its nesting, mixins etc.
You can even directly use bootstrap's mixins when writing your less styles.
To learn more about Less-CSS, please take a look at http://lesscss.org/
Of course if you don't want to use less, you can simply write your own CSS, but again NOT in the assets/css/app.css file.
Instead, use the assets/css/app.less file.
When a change in that file is detected, the less compiler minifies it and the output is written in the assets/css/app.css file.
If you apply your changes and you don't see them applied in your theme, 
please make sure that it is writable and your server has write permissions for the assets/css/app.css file.

## Automatic Updates

This theme provides automatic updates.
When you get this theme from http://bootstrap-commerce.com/downloads/downloads/shoepress/ a licence key will be emailed 
which when entered and activated will provide you with automatic updates

## Features

* HTML5 Boilerplate’s markup and `.htaccess`
* Bootstrap from Twitter
* Theme wrapper
* Root relative URLs
* Clean URLs (no more `/wp-content/`)
* All static theme assets are rewritten to the website root (`/assets/css/`, `/assets/img/`, and `/assets/js/`)
* Cleaner HTML output of navigation menus
* Cleaner output of `wp_head` and enqueued scripts/styles
* Posts use the [hNews](http://microformats.org/wiki/hnews) microformat
* Multilingual ready
* Extended use of WordPress's customizer (introduced in WordPress 3.4
* Uses less for styling and includes a php-less compiler.
* The compiled css is minified.

## Customizer Options

### Header & Logo

* Upload a logo image
* Change the header region background color
* Change the header text color. This setting affects the color of your site-name when you haven’t uploaded a logo, as well as the color of your social links icons.
* Selection of Navbar color

### Layout

* Left Sidebar
* Right Sidebar (default)
* No Sidebar

### Typography

* Choose from 550+ Google Webfonts for your site

### Footer

* Select the background color for your footer.

### Hero Region

* Title
* Content (accepts HTML)
* Call To Action Button label
* Call To Action Button link
* Call To Action Button color (select from 5 variations)
* Background Color
* Background Image
* Text Color
* Visibility of the Hero Region (Frontpage only or site-wide)

### Social Links

* Facebook link
* Twitter link
* Google+ link
* Pinterest link

### Colors
* Dark/Light text (defaults to dark)
* Links color
* Buttons color
* Background color
* Background Image
* Upload a background image

### Navigation

* Select a WordPress Menu for your navbar navigation
