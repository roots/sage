=== Advanced Custom Fields: Options Page ===
Contributors: elliotcondon
Author: Elliot Condon
Author URI: http://www.elliotcondon.com
Plugin URI: http://www.advancedcustomfields.com
Requires at least: 3.0
Tested up to: 3.6.0
Stable tag: trunk
Homepage: http://www.advancedcustomfields.com/add-ons/options-page/
Version: 1.2.0


== Copyright ==
Copyright 2011 - 2013 Elliot Condon

This software is NOT to be distributed, but can be INCLUDED in WP themes and Plugins: Premium or Contracted.
If you include this software within a premium theme or premium plugin, you MUST remove the acf-options-page-update.php file from the folder.

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Description ==

= Global Options have never been so easy =

The “options page” add-on creates a new menu item called “Options” which can hold advanced custom field groups (just like any other edit page). You can also register multiple options pages

http://www.advancedcustomfields.com/add-ons/options-page/


== Installation ==

This software can be treated as both a WP plugin and a theme include.
However, only when activated as a plugin will updates be available/

= Plugin =
1. Copy the 'acf-options-page' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

= Include =
1. Copy the 'acf-options-page' folder into your theme folder (can use sub folders)
   * You can place the folder anywhere inside the 'wp-content' directory
2. Edit your functions.php file and add the following code to include the field:

`
include_once('acf-options-page/acf-options-page.php');

`

3. Make sure the path is correct to include the acf-options-page.php file
4. Remove the acf-options-page-update.php file from the folder.


== Changelog ==

= 1.2.0 =
* Added Polish translation - Thanks to matczar (http://support.advancedcustomfields.com/forums/users/matczar/)
* Added function acf_set_options_page_menu()
* Added new param 'menu' to the acf_add_options_sub_page function

= 1.1.0 =
* Big thank you to Edir Pedro (http://edirpedro.com.br) for his contribution to this version!
* Added function acf_add_options_sub_page()
* Added function acf_set_options_page_title()
* Added function acf_set_options_page_capability()
* Improved sub page functionality to allow for individual title, capability, parent and slug. This allows you to place the sub page onto any parent page in the wp-admin menu!
* Added lang folder including .pot file
* Added Portuguese translation - Thanks to Edir Pedro (http://edirpedro.com.br)

= 1.0.1 =
* wrapped the register_options_page function in an if statement to prevent error when activation this add-on with ACF v3

= 1.0.0 =
* [Updated] Updated update_field parameters
* Official Release

= 0.0.4 =
* [Updated] Update nonce name to 'acf_nonce' => 'input' to match naming convention

= 0.0.3 =
* [Updated] Drop support of old filters / actions

= 0.0.2 =
* Fixed errors caused by an update to the core functions.

= 0.0.1 =
* Initial Release.
