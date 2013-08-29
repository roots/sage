<?php

//include_once(dirname(__FILE__).'/tgm-init.php');

/**
 
  Require the framework class before doing anything else, so we can use the defined urls and dirs
  Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 
**/

// Try to include the framework if it is embedded in the theme.
if (strpos(dirname(__FILE__),TEMPLATEPATH) !== false && !class_exists('Simple_Options') && file_exists( dirname( __FILE__ ) . '/options/options.php') ) {
	include_once( dirname( __FILE__ ) . '/SimpleOptions/options.php' );
}


function shoestrap_simpleoptions_init(){
	if (class_exists("Simple_Options")) {
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

		//Add HTML before the form
		//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isn\'t required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'simple-options');

		//Setup custom links in the footer for share icons
		$args['share_icons']['twitter'] = array(
			'link' => 'https://github.com/shoestrap/shoestrap',
			'title' => 'For Me on GitHub', 
			'img' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_341_github.png'
			);

		//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
		$args['opt_name'] = 'shoestrap';

		//Custom menu icon
		//$args['menu_icon'] = '';

		//Custom menu title for options page - default is "Options"
		$args['menu_title'] = wp_get_theme();

		//custom page location - default 100 - must be unique or will override other items
		$args['page_position'] = 27;

		//Custom page icon class (used to override the page icon next to heading)
		//$args['page_icon'] = 'icon-themes';

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

		$sections = array();
					
		$tabs = array();
		
		if(file_exists(trailingslashit(get_stylesheet_directory()).'README.md')){
			$tabs['theme_docs'] = array(
							'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
							'title' => __('Documentation', 'simple-options'),
							'content' => file_get_contents(get_stylesheet_directory().'/README.md')
							);
		}//if

		$sections = apply_filters('shoestrap_add_sections', $sections);

		$Simple_Options = new Simple_Options($sections, $args, $tabs);
	}

}//function
add_action('init', 'shoestrap_simpleoptions_init');

/**
	Saving functions on import, etc
**/
add_action('simple-options-compiler-shoestrap', 'shoestrap_makecss'); // If a compiler field was altered or import or reset defaults


