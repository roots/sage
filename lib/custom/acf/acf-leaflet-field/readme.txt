=== Advanced Custom Fields: Leaflet Field ===
Contributors: jensnilsson
Tags: Advanced Custom Fields, acf, acf4, custom fields, admin, wp-admin, map, leaflet, map markers
Requires at least: 3.4
Tested up to: 3.5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Addon for Advanced Custom Fields that adds a Leaflet field to the available field types.

== Description ==

This plugin adds a [Leaflet](http://leafletjs.com) map field to the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) plugin. Use it to display beutiful maps with markers along with your posts and pages.

* Add multiple markers with popups to the map.
* The field stores both your zoom-level and viewport location.
* Function to render the map in your theme is included in the plugin: `<?php the_leaflet_field( $field_name ); ?>`, just plug and play!

== Installation ==

1. Upload `advanced-custom-fields-leaflet-field` to the `/wp-content/plugins/` directory
1. Make sure you have [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) installed and activated
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Register for an account at CloudMade to get an API-key
1. Add a Leaflet field to a ACF field group and save
1. The field is now ready for use

== Instructions ==

A rendering function is provided in the plugin. If you want to use it all you have to do is use `the_leaflet_field( $field_name );` where you want to render the map. I recommended you to build your own rendering though.

== To do ==
Things I plan to add to the plugin:

1. Adding images to popups.
1. Support for drawing polylines.
1. Provide a tool for importing GeoJSON-structured data into the field.

== GitHub ==

If you want the latest development version of this plugin it is available over at my [github repository](https://github.com/jensjns/acf-leaflet-field/). The github repository will always have the latest code and may occasionally be broken and not work at all.

== Frequently Asked Questions ==

= I activated the plugin but nothing cool happened ;( =

This is not a standalone plugin, you need to have [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) installed and activated for it to work.

== Screenshots ==

1. Leaflet field with some markers.
2. Settings for the Leaflet field.

== Changelog ==

= 1.1.0 =
* Added support for OpenStreetMap as tile-provider. This will be the new default tile-provider.
* Switched to geoJson for data-structure.

= 1.0.0 =
* Migrated the plugin into field-template for v3 & v4 support.
* New tool: Locate tool.
* Fixed repeater-support.

= 0.2.0 =
* Added support for binding popups to markers.

= 0.1.0 =
* Release

== Upgrade Notice ==

= 1.1.0 =
* New tile-provider: OpenStreetMap, which is now the default tile-provider since it doesn't require an api-key.
* Changed the data-structure to geoJson. IMPORTANT: Breaking change. Sorry.

= 1.0.0 =
* New tool: Locate tool.
* The field now works with the repeater-field.

= 0.2.0 =
* New feature: Added support for binding popups to markers.

= 0.1.0 =
* Release