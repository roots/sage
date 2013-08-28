<?php
/**
 
  Require the framework class before doing anything else, so we can use the defined urls and dirs
  Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 
**/

// Try to include the framework if it is embedded in the theme.
if (strpos(dirname(__FILE__),TEMPLATEPATH) !== false &&!class_exists('Simple_Options') && file_exists( dirname( __FILE__ ) . '/options/options.php') ) {
	include_once( dirname( __FILE__ ) . '/options/options.php' );
}

// Return if the class can't be found. No errors please!
if( !class_exists('Simple_Options') ){
	return;
}

/**

  Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
  Simply include this function in the child themes functions.php file.
 
  NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
  get_template_directory_uri() if you want to use any of the built in icons
 
**/
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'simple-options'),
				'description' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'simple-options'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function
//add_filter('simple-options-sections-twenty_eleven', 'add_another_section');







/**

	Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.

**/
function change_framework_args($args){
	//$args['dev_mode'] = false;
	return $args;
	
}//function
//add_filter('simple-options-args-twenty_eleven', 'change_framework_args');









/**
 
  This is the meat of creating the optons page
 
  Override some of the default values, uncomment the args and change the values
  - no $args are required, but there there to be over ridden if needed.
 
**/


function shoestrap_simpleoptions_init(){
	global $Simple_Options;

	if (!empty($Simple_Options)) {
		return;
	}

	$args = array();

	//Set it to dev mode to view the class settings/info in the form - default is false
	$args['dev_mode'] = true;

	// Enable customizer support for all of the fields unless denoated as customizer=>false in the field declaration
	$args['customizer'] = true;

	//google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
	$args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';
	// ** PLEASE PLEASE for production use your own key! **

	//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
	//$args['stylesheet_override'] = true;

	//Add HTML before the form
	//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isn\'t required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'simple-options');

	//Setup custom links in the footer for share icons
	$args['share_icons']['twitter'] = array(
		'link' => 'http://twitter.com/simplerain',
		'title' => 'Folow me on Twitter', 
		'img' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png'
		);
	$args['share_icons']['linked_in'] = array(
		'link' => 'http://linkedin.com/in/dovyp',
		'title' => 'Find me on LinkedIn', 
		'img' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_337_linked_in.png'
		);

	//Choose to disable the import/export feature
	//$args['show_import_export'] = false;

	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
	$args['opt_name'] = 'shoestrap';

	//Custom menu icon
	//$args['menu_icon'] = '';

	//Custom menu title for options page - default is "Options"
	$args['menu_title'] = wp_get_theme();

	//Custom Page Title for options page - default is "", and thus hidden
	//$args['page_title'] = wp_get_theme() . ' '.__('Theme Options', 'simple-options');

	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "simple_theme_options"
	//$args['page_slug'] = 'simple_theme_options';

	//Custom page capability - default is set to "manage_options"
	//$args['page_cap'] = 'manage_options';

	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	//$args['page_type'] = 'submenu';

	//parent menu - default is set to "themes.php" (Appearance)
	//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$args['page_parent'] = 'themes.php';

	//custom page location - default 100 - must be unique or will override other items
	$args['page_position'] = 27;

	//Custom page icon class (used to override the page icon next to heading)
	//$args['page_icon'] = 'icon-themes';

	//Want to disable the sections showing as a submenu in the admin? uncomment this line
	//$args['allow_sub_menu'] = false;
			
	//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition		
	
	$args['help_tabs'][] = array(
								'id' => 'simple-options-1',
								'title' => __('Theme Information 1', 'simple-options'),
								'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'simple-options')
								);
	$args['help_tabs'][] = array(
								'id' => 'simple-options-2',
								'title' => __('Theme Information 2', 'simple-options'),
								'content' => __('<p>This is the tab content, HTML is allowed. Tab2</p>', 'simple-options')
								);

	//Set the Help Sidebar for the options page - no sidebar by default										
	$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'simple-options');


/**

$wp_customize->add_section($id, $args);

Arguments:
title
The visible name of a controller section.
priority
This controls the order in which this section appears in the Theme Customizer sidebar.
description
This optional argument can add additional descriptive text to the section.
Icon
The icon used by the theme.
Header
The header text. If none is supplied, the title is used.

**/

$sections = array();
				
	$tabs = array();
			
	if (function_exists('wp_get_theme')){
		$theme_data = wp_get_theme();
		$theme_uri = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	}else{
		$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
		$theme_uri = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}	

	$theme_info = '<div class="simple-options-section-desc">';
	$theme_info .= '<p class="simple-options-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'simple-options').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'simple-options').$author.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'simple-options').$version.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-description">'.$description.'</p>';
	$theme_info .= '<p class="simple-options-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'simple-options').implode(', ', $tags).'</p>';
	$theme_info .= '</div>';



	$tabs['theme_info'] = array(
					'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_195_circle_info.png',
					'title' => __('Theme Information', 'simple-options'),
					'content' => $theme_info
					);
	
	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.md')){
		$tabs['theme_docs'] = array(
						'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'simple-options'),
						'content' => file_get_contents(get_stylesheet_directory().'/README.md')
						);
	}//if

	$sections = apply_filters('shoestrap_add_sections', $sections);

	
	$Simple_Options = new Simple_Options($sections, $args, $tabs);

	do_action('shoestrap_add_sections_hook');
/*
    // Convert options from SMOF to SOF
    global $of_options;

    $section = array();
    $fields = array();
    $old = $of_options;
    foreach ($of_options as $k => $item) {
    	if ($item['type'] == "heading") {
    		if (!empty($fields)) {
    			$section['fields'] = $fields;
    			$fields = array();
    			array_push($Simple_Options->sections, $section);
    		}
	    	$section = array(
	    		'icon' 		=> SOF_OPTIONS_URL.'img/glyphicons/glyphicons_157_show_lines.png',
		      "title"   => $item['name'],
	    	);
    	} else if ($item['type'] == "backup") {
    		continue;
    	} else if ($item['type'] == 'transfer') {
				continue;
    	} else if ($item['type'] == "select_google_font_hybrid"){
    		$item['type'] = "typography";
    	} else {

    		if ($item['type'] == "sliderui") {
    			$item['type'] = "slider";
    		} else if ($item['type'] == "tiles") {
    			$item['type'] = "images";
    			$item['tiles'] = true;
    		}	else if ($item['type'] == "multicheck") {
					$item['type'] == "multi_checkbox";
				} else if ($item['type'] == "slider" && !empty($item['name'])) {
					$item['type'] == "slides";
				} else if ($item['type'] == "upload") {
					$item['type'] == "media";
				} else if ($item['type'] == "select_google_font" || $item['type'] == "select_google_font_hybrid") {
					$item['type'] == "typography";
				} else if ($item['type'] == "images") {
					$item['type'] == "radio_images";
				} 

				if (!empty($item['fold'])) {
					$item['fold'] = array($item['fold']);
				}

    		array_push($fields, $item);
    	}
    }
		if (!empty($fields)) {
			$section['fields'] = $fields;
			$fields = array();
			array_push($Simple_Options->sections, $section);
		}    
*/


	//echo $Simple_Options->value('footer-text');
}//function
add_action('init', 'shoestrap_simpleoptions_init');

/*
 * 
 * Custom function to change the display name of a section for the menu
 *
 */
function change_home_menu_name($section) {
	//$section['title'] = "This is a test";
	return $section;
}
add_action('home_settings_section_menu_modifier', 'change_home_menu_name', 0);


/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function



/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function



/**
	Saving functions on import, etc
**/

add_action('simple-options-after-import-shoestrap', 'shoestrap_makecss'); // If an import occurred
add_action('simple-options-run-compiler-shoestrap', 'shoestrap_makecss'); // If a compiler field was altered
add_action('simple-options-after-defaults-shoestrap', 'shoestrap_makecss'); // If defaults are set (reset to defaults)
