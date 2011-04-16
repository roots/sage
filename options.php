<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
#	// Test data
#	$test_array = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
#	
#	// Multicheck Array
#	$multicheck_array = array("one" => "French Toast", "two" => "Pancake", "three" => "Omelette", "four" => "Crepe", "five" => "Waffle");
#	
#	// Multicheck Defaults
#	$multicheck_defaults = array("one" => "true","five" => "true");
#	
#	// Background Defaults
#	
#	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
#	
#	
#	// Pull all the categories into an array
#	$options_categories = array();  
#	$options_categories_obj = get_categories();
#	foreach ($options_categories_obj as $category) {
#    	$options_categories[$category->cat_ID] = $category->cat_name;
#	}
#	
#	// Pull all the pages into an array
#	$options_pages = array();  
#	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
#	$options_pages['false'] = 'Select a page:';
#	foreach ($options_pages_obj as $page) {
#    	$options_pages[$page->ID] = $page->post_title;
#	}
#		
#	// If using image radio buttons, define a directory path
#	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
	$options = array();
		
	$options[] = array( "name" => "General Roots Settings",
						"type" => "heading");

	$roots_available_grid_framework = array('roots_blueprint' => 'Blueprint', 'roots_960gs_12' => '960gs (12 cols)', 'roots_960gs_16' => '960gs (16 cols)', 'roots_960gs_24' => '960gs (24 cols)', 'roots_1140gs' => '1140gs');

	$options[] = array( "name" => "CSS Grid Framework",
						"desc" => "Please select your css grid framework",
						"id" => "roots_css_framework",
						"std" => "roots_blueprint",
						"type" => "radio",
						"options" => $roots_available_grid_framework);
							
	$options[] = array( "name" => "Class for #main",
						"desc" => "Enter your grid classes",
						"id" => "roots_main_class",
						"std" => "span-14 append-1",
						"type" => "text");

	$options[] = array( "name" => "Class for #sidebar",
						"desc" => "Enter your grid classes",
						"id" => "roots_sidebar_class",
						"std" => "span-8 prepend-1 last",
						"type" => "text");

	$options[] = array( "name" => "Google Analytics Tracking ID",
						"desc" => "Enter your UA-XXXXX-X ID",
						"id" => "roots_google_analytics",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Display Post Author",
						"desc" => "Show the post author",
						"id" => "roots_post_author",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => "Post Tweet Button",
						"desc" => "Enable Tweet button on posts",
						"id" => "roots_post_tweet",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => "Footer Social Share Buttons",
						"desc" => "Enable official Twitter and Facebook buttons in the footer",
						"id" => "roots_footer_social_share",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => "Footer vCard",
						"type" => "heading");

	$options[] = array( "name" => "Footer vCard",
						"desc" => "Enable vCard in the footer",
						"id" => "roots_footer_vcard",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => "Street Address",
						"desc" => "",
						"id" => "roots_vcard_street-address",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "City",
						"desc" => "",
						"id" => "roots_vcard_locality",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "State",
						"desc" => "",
						"id" => "roots_vcard_region",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Zipcode",
						"desc" => "",
						"id" => "roots_vcard_postal-code",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Telephone Number",
						"desc" => "",
						"id" => "roots_vcard_tel",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Email address",
						"desc" => "",
						"id" => "roots_vcard_email",
						"std" => "",
						"type" => "text");

	return $options;
}
