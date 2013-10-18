<?php
/*
Plugin Name: shortcode for Advanced Custom Fields
Plugin URI: 
Description: When you enter a shortcode, it will be executed and outputted, by using the_field('FIELD_NAME_HERE') in your theme.
Version: 4.0
Author: Fumito MIZUNO
Author URI: http://wp.php-web.net/
License: GPL
*/

function shortcode_field_init(){
load_plugin_textdomain('sadvanced-custom-fields-shortcode', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}
add_action('plugins_loaded', 'shortcode_field_init');

add_action('acf/register_fields', 'shortcode_acf_register_fields');

function shortcode_acf_register_fields() {
	include_once('shortcode_field.php');
}	
