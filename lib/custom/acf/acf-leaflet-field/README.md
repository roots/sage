ACF Leaflet field
=================
The latest stable release can be found [here](http://wordpress.org/extend/plugins/advanced-custom-fields-leaflet-field/).
Description
-----------
Addon for [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) that adds a Leaflet map-field to the available field types.

Installation
------------
1. Upload acf-leaflet-field to the /wp-content/plugins/ directory.
1. Make sure you have [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) installed and activated
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Register for an account at CloudMade and get you API-key.
1. Add a Leaflet field to a ACF field group and save

Instructions
------------
Use ```the_leaflet_field( $field_name );``` where you want to render the map.