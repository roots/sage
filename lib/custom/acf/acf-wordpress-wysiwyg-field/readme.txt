=== Advanced Custom Fields: WordPress WYIWYG Field ===
Contributors: elliotcondon
Tags: 
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a native WordPress WYSIWYG field to the Advanced Custom Fields plugin. Please note this field does not work as a sub field

== Description ==

Adds a native WordPress WYSIWYG field to the Advanced Custom Fields plugin. 

This field uses the native wp_editor function which creates a very native looking WYSWYG field. The only downside to this field is that it does not work within a repeater field when adding a new row. Saving the post and reloading the edit screen will allow the field to render correctly and continue to work as expected.

= Compatibility =

This add-on will work with:

* version 4 and up
* version 3 and bellow

== Installation ==

This add-on can be treated as both a WP plugin and a theme include.

= Plugin =

1. Download .zip
2. Extract .zip and rename folder to 'acf-wp-wysiwyg'
3. Copy the 'acf-wp-wysiwyg' folder into your plugins folder
4. Activate the plugin via the Plugins admin page

= Include =

1. Download .zip
2. Extract .zip and rename folder to 'acf-wp-wysiwyg'
3.	Copy the 'acf-wp-wysiwyg' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
4.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-wp-wysiwyg.php file)

`
include_once('acf-wp_wysiwyg/acf-wp_wysiwyg.php');
`

== Changelog ==

= 1.0.0 =
* Initial Release.
