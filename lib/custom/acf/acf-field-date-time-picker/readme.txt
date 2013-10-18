=== Date and Time Picker Field ===
Contributors: PerS
Donate link: http://soderlind.no/donate/
Tags: acf, custom field,datepicker,timepicker
Requires at least: 3.4
Tested up to: 3.6
Stable tag: 2.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Date and Time Picker field for Advanced Custom Fields

== Description ==


This is an add-on for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin, that allows you to add a Date and Time Picker field type.

[youtube http://www.youtube.com/watch?v=Mumx4HGOljQ]

= Compatibility =

This add-on will work with:

* Advanced Custom Fields version 4 and up
* Advanced Custom Fields version 3 and bellow

= More Information =

[http://soderlind.no/time-picker-field-for-advanced-custom-fields/](http://soderlind.no/time-picker-field-for-advanced-custom-fields/)

== Installation ==


= Plugin =
1. Copy the 'acf-date_time_picker' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

= Include =
1.	Copy the 'acf-date_time_picker' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-date_time_picker.php file)

`
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	include_once('acf-date_time_picker/acf-date_time_picker.php');
}
`


== Screenshots ==

1. Add the Date and Time Picker field
2. Date and Time Picker
3. Time Picker


== Frequently Asked Questions  ==


**How do I set the date and time format?**

To set  the date and time format when you create the field, you have to create a string using the letters below.

= Date format =

`
d    day of month (no leading zero)
dd   day of month (two digit)
o    day of the year (no leading zeros)
oo   day of the year (three digit)
D    day name short
DD   day name long
m    month of year (no leading zero)
mm   month of year (two digit)
M    month name short
MM   month name long
y    year (two digit)
yy   year (four digit)
`

= Time format =

`
H    Hour with no leading 0 (24 hour)
HH   Hour with leading 0 (24 hour)
h    Hour with no leading 0 (12 hour)
hh   Hour with leading 0 (12 hour)
m    Minute with no leading 0
mm   Minute with leading 0
s    Second with no leading 0
ss   Second with leading 0
l    Milliseconds always with leading 0
t    a or p for AM/PM
T    A or P for AM/PM
tt   am or pm for AM/PM
TT   AM or PM for AM/PM
`


= Examples =

* `yy-mm-dd`: 2013-04-12
* `HH:mm`: 24 hour clock, with a leading 0 for hour and minute
* `h:m tt`: 12 hour clock with am/pm, no leading 0

**How do I format the date and time when I want to use it in my theme?**

The Date and Time Picker field is saved as an UNIX timestamp. Use the PHP [date](http://php.net/manual/en/function.date.php) function when you use it in your theme.

== Changelog ==
= 2.0.11 =
* Added option to retrive field values, using the_field() and get_field(), as a timestamp
= 2.0.10 =
* Removed "value" from defaults
= 2.0.9 =
* Thanks to [flahertydaf](http://support.advancedcustomfields.com/forums/topic/custom-fields-get-emptied-when-publishing/page/2/#post-2325), the plugin in now working with the latest ACF version
* Replaced DateTime::createFromFormat (PHP 5 >= 5.3.0), with strtotime
* minor bugfixes
= 2.0.8 =
* Adds option to store the date and time field as a UNIX timestamp or not.
= 2.0.7 =
* Bug fix. 2.0.6 assumed that the stored date and time was in UNIX timestamp format. 2.0.7 will check and only convert if the date and time is.
= 2.0.6 =
* Changed how the Date and Time Picker field is triggered when ACF adds a new Date and Time Picker field to the DOM
* Saves the Date and Time Picker field as an UNIX timestamp to MySQL. Use the PHP [date](http://php.net/manual/en/function.date.php) function  when you use it in your theme.
= 2.0.5 =
* When enqueuing JavaScripts, replaced dependecy of jquery-ui-datepicker with acf-datepicker
= 2.0.4 =
* Updated JavaScript [language detection and loading](http://soderlind.no/time-picker-field-for-advanced-custom-fields/#localization)
= 2.0.3 =
* Fixed Repeater field bug
* Added support for including the field in a theme
= 2.0.2 =
* Updated readme.txt
= 2.0.1 =
* Minor fix
= 2.0.0.beta =
* Total rewrite, based on the [acf-field-type-template](https://github.com/elliotcondon/acf-field-type-template). Works with ACF v3 and ACF v4. In this beta you can only add the Date Time Picker field as a plugin (i.e. not as a template field).
= 1.2.0 =
* Updated jquery-ui-timepicker-addon.js to the latest version (1.0.0) and added localization support.
= 1.1.1 =
* Fixed a small bug
= 1.1 =
* Change name to Date and Time Picker to reflect the new option to select between Date and Time picker or Time Picker only. Thanks to Wilfrid for point this out (not sure why I didn’t include it in 1.0)
= 1.0 =
* Initial version
