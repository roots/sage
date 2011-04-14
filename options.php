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
							
	$options[] = array( "name" => "Input Text Mini",
						"desc" => "A mini text input field.",
						"id" => "example_text_mini",
						"std" => "Default",
						"class" => "mini",
						"type" => "text");
								
	$options[] = array( "name" => "Input Text",
						"desc" => "A text input field.",
						"id" => "example_text",
						"std" => "Default Value",
						"type" => "text");
							
	$options[] = array( "name" => "Textarea",
						"desc" => "Textarea description.",
						"id" => "example_textarea",
						"std" => "Default Text",
						"type" => "textarea"); 
						
	$options[] = array( "name" => "Input Select Small",
						"desc" => "Small Select Box.",
						"id" => "example_select",
						"std" => "three",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $test_array);			 
						
	$options[] = array( "name" => "Input Select Wide",
						"desc" => "A wider select box.",
						"id" => "example_select_wide",
						"std" => "two",
						"type" => "select",
						"options" => $test_array);
						
	$options[] = array( "name" => "Select a Category",
						"desc" => "Passed an array of categories with cat_ID and cat_name",
						"id" => "example_select_categories",
						"type" => "select",
						"options" => $options_categories);
						
	$options[] = array( "name" => "Select a Page",
						"desc" => "Passed an pages with ID and post_title",
						"id" => "example_select_pages",
						"type" => "select",
						"options" => $options_pages);
						
	$options[] = array( "name" => "Input Radio (one)",
						"desc" => "Radio select with default options 'one'.",
						"id" => "example_radio",
						"std" => "one",
						"type" => "radio",
						"options" => $test_array);
							
	$options[] = array( "name" => "Example Info",
						"desc" => "This is just some example information you can put in the panel.",
						"type" => "info");
											
	$options[] = array( "name" => "Input Checkbox",
						"desc" => "Example checkbox, defaults to true.",
						"id" => "example_checkbox",
						"std" => "true",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Advanced Settings",
						"type" => "heading");
						
	$options[] = array( "name" => "Check to Show a Hidden Text Input",
						"desc" => "Click here and see what happens.",
						"id" => "example_showhidden",
						"std" => "false",
						"type" => "checkbox");
	
	$options[] = array( "name" => "Hidden Text Input",
						"desc" => "This option is hidden unless activated by a checkbox click.",
						"id" => "example_text_hidden",
						"std" => "Hello",
						"class" => "hidden",
						"type" => "text");
						
	$options[] = array( "name" => "Uploader Test",
						"desc" => "This creates a full size uploader that previews the image.",
						"id" => "example_uploader",
						"type" => "upload");
						
	$options[] = array( "name" => "Example Image Selector",
						"desc" => "Images for layout.",
						"id" => "example_images",
						"std" => "2c-l-fixed",
						"type" => "images",
						"options" => array(
							'1col-fixed' => $imagepath . '1col.png',
							'2c-r-fixed' => $imagepath . '2cr.png',
							'2c-l-fixed' => $imagepath . '2cl.png',
							'3c-fixed' => $imagepath . '3cm.png',
							'3c-r-fixed' => $imagepath . '3cr.png')
						);
						
	$options[] = array( "name" =>  "Example Background",
						"desc" => "Change the background CSS.",
						"id" => "example_background",
						"std" => $background_defaults, 
						"type" => "background");
								
	$options[] = array( "name" => "Multicheck",
						"desc" => "Multicheck description.",
						"id" => "example_multicheck",
						"std" => $multicheck_defaults, // These items get checked by default
						"type" => "multicheck",
						"options" => $multicheck_array);
							
	$options[] = array( "name" => "Colorpicker",
						"desc" => "No color selected by default.",
						"id" => "example_colorpicker",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => "Typography",
						"desc" => "Example typography.",
						"id" => "example_typography",
						"std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
						"type" => "typography");			
	return $options;
}
