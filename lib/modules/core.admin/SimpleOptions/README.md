Simple Options Framework
=============

Simple Options Framework (SOF) was created to combine the best features of some of the most effective features on the market. Also motivated by the changes to the marketplace put forth by ThemeForest.

SOF is a back-end framework for creating and managing options inside WordPress themes. It cuts off the process of developing your own admin framework and give you more time to actually focus on building your theme. SOF comes bundled with plentiful of options that should serve most of the needs of any modern theme authors.

The main feature of SMOF is its intuitive, user-friendly interface that aims to look as native as possible to WordPress. Additionally, it comes loaded with some awesome features that makes it even more usable to you as a theme author. 

## Donate to the Framework
Should you feel pleased with Simple Options, consider donating to the developer. The time that has been put into this framework has been intense and I've given it away. Every contribution helps.

[![Donate to the framework](https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif "Donate to the framework")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3WQGEY4NSYE38)

## Downloading
### If you download the zip you get the development version. To get a stable version, please visit here and find the latest release: 
### [https://github.com/SimpleRain/SimpleOptions/releases](https://github.com/SimpleRain/SimpleOptions/releases)

## Try it out
To make your life easier we've created a test theme. With the benefits of SOF's auto-updating you will always be sure to get the latest updates.

[https://github.com/SimpleRain/SimpleOptions-Test-Theme](https://github.com/SimpleRain/SimpleOptions-Test-Theme)




## Usage Examples
Simple Options can be run in two configuration. The preferrable way is to run it as a plugin. We've baked automated updates into Simple Options so users will get all the benefits of improvements without theme admin's involvement. To use SOF as a plugin, do the following:
* Grab the latest release from here: [https://github.com/SimpleRain/SimpleOptions/releases](https://github.com/SimpleRain/SimpleOptions/releases)
* Install SOF as a plugin
* Copy options-init.php to inside your theme.
* Include the ```options-init.php``` file in your theme's ```functions.php``` file, like so:

```php
get_template_part('options', 'init');
```

You can also run SOF outside of a plugin, embedded in a theme. By so doing users lose the automatic updates to the core files. To do this:
* Grab the latest release from here: https://github.com/SimpleRain/SimpleOptions/releases
* Delete the plugin.php file (it's the plugin specific file)
* Include and implement the framework by adding the following code to your ```functions.php``` file:

```php
<?
$plugins = get_option('active_plugins');
foreach (get_option('active_plugins') as $plugin) {
	if (strpos(strtolower($plugin),'simpleoptions') !== false) {
		$folder = plugin_dir_path($plugin);
		include_once(WP_PLUGIN_DIR.'/'.$folder."options-init.php");
	}
}
```



**Please note if you embed the framework into your theme (not as a plugin) your users will depend on you to update the framework to recieve core updates. It is advisable to use SOF as a plugin.**


## Features 
### Working on outlining this proper. Please be patient -dovy
* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Multple layout field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes (more info in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Built in Validation Classes: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki title="View WIKI")
* Easily extendable by creating Validation Classes (more in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Native Media Library Uploader
* Native WP Color Picker
* Drag and Drop Unlimited Slider Options
* Layout Manager
* Tiles
* Backup and Restore
* Keeps the site URL out of the database
* Google fonts with live preview
* Jquery UI slider
* ...and much more(including base elements inputs, textarea, etc.)

## Credits
SOF is a fork of the following option frameworks. Each have their own credits outlined on their project pages.

* [Slightly Modded Options Framework v1.5.2](https://github.com/sy4mil/Options-Framework)
	* Used because it has a very complete UI and widgets.
* [NHP Theme Options v1.0.6](https://github.com/leemason/NHP-Theme-Options-Framework)
	* Used because of excellent PHP development and hook usage.

### License

SOF is released under GPLv3 - [http://www.gnu.org/copyleft/gpl.html](http://www.gnu.org/copyleft/gpl.html). You are free to redistribute & modify copies of the plugin under the following conditions:

* All links & credits must be kept intact
* <b>For commercial usage</b> (e.g in themes you're selling on any marketplace, or a commercial website), you are **strongly recommended** to link back to my [Themeforest Profile Page](http://themeforest.net/user/SimpleRain) using the following text: [Simple Options Framework](https://github.com/SimpleRain/SimpleOptions) by [SimpleRain](http://themeforest.net/user/SimpleRain).

### Contacts

Twitter: http://twitter.com/simplerain

Website: http://simplerain.com
