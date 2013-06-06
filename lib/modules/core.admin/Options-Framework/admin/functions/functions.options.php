<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();  
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select 	= array("one","two","three","four","five"); 
		$of_options_radio 	= array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			), 
			"enabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

$of_options[] = array( 	"name" 		=> "Home Settings",
						"type" 		=> "heading"
				);
					
$of_options[] = array( 	"name" 		=> "Hello there!",
						"desc" 		=> "",
						"id" 		=> "introduction",
						"std" 		=> "<h3 style=\"margin: 0 0 10px;\">Welcome to the Options Framework demo.</h3>
						This is a slightly modified version of the original options framework by Devin Price with a couple of aesthetical improvements on the interface and some cool additional features. If you want to learn how to setup these options or just need general help on using it feel free to visit my blog at <a href=\"http://aquagraphite.com/2011/09/29/slightly-modded-options-framework/\">AquaGraphite.com</a>",
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> "Media Uploader 3.5",
						"desc" 		=> "Upload images using native media uploader from Wordpress 3.5+.",
						"id" 		=> "media_upload_35",
						// Use the shortcodes [site_url] or [site_url_secure] for setting default URLs
						"std" 		=> "",
						"type" 		=> "upload"
				);

$of_options[] = array( 	"name" 		=> "Media Uploader 3.5 min",
						"desc" 		=> "Upload images using native media uploader from Wordpress 3.5+. Min mod",
						"id" 		=> "media_upload_356",
						// Use the shortcodes [site_url] or [site_url_secure] for setting default URLs
						"std" 		=> "",
						"mod"		=> "min",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> "JQuery UI Slider example 1",
						"desc" 		=> "JQuery UI slider description.<br /> Min: 1, max: 500, step: 3, default value: 45",
						"id" 		=> "slider_example_1",
						"std" 		=> "45",
						"min" 		=> "1",
						"step"		=> "3",
						"max" 		=> "500",
						"type" 		=> "sliderui" 
				);
				
$of_options[] = array( 	"name" 		=> "JQuery UI Slider example 1 with steps(5)",
						"desc" 		=> "JQuery UI slider description.<br /> Min: 0, max: 300, step: 5, default value: 75",
						"id" 		=> "slider_example_2",
						"std" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						"type" 		=> "sliderui" 
				);

$of_options[] = array( 	"name" 		=> "JQuery UI Spinner",
						"desc" 		=> "JQuery UI spinner description.<br /> Min: 0, max: 300, step: 5, default value: 75",
						"id" 		=> "spinner_example_2",
						"std" 		=> "75",
						"min" 		=> "0",
						"step"		=> "5",
						"max" 		=> "300",
						"type" 		=> "spinner" 
				);
				
$of_options[] = array( 	"name" 		=> "Switch 1",
						"desc" 		=> "Switch OFF",
						"id" 		=> "switch_ex1",
						"std" 		=> 0,
						"type" 		=> "switch"
				);   
				
$of_options[] = array( 	"name" 		=> "Switch 2",
						"desc" 		=> "Switch ON",
						"id" 		=> "switch_ex2",
						"std" 		=> 1,
						"type" 		=> "switch"
				);
				
$of_options[] = array( 	"name" 		=> "Switch 3",
						"desc" 		=> "Switch with custom labels",
						"id" 		=> "switch_ex3",
						"std" 		=> 0,
						"on" 		=> "Enable",
						"off" 		=> "Disable",
						"type" 		=> "switch"
				);
				
$of_options[] = array( 	"name" 		=> "Switch 4",
						"desc" 		=> "Switch OFF with hidden options. ;)",
						"id" 		=> "switch_ex4",
						"std" 		=> 0,
						"folds"		=> 1,
						"type" 		=> "switch"
				);
				
$of_options[] = array( 	"name" 		=> "Hidden option 1",
						"desc" 		=> "This is a sample hidden option controlled by a <strong>switch</strong> button",
						"id" 		=> "hidden_switch_ex1",
						"std" 		=> "Hi, I\'m just a text input - nr 1",
						"fold" 		=> "switch_ex4", /* the switch hook */
						"type" 		=> "text"
				);
				
$of_options[] = array( 	"name" 		=> "Hidden option 2",
						"desc" 		=> "This is a sample hidden option controlled by a <strong>switch</strong> button",
						"id" 		=> "hidden_switch_ex2",
						"std" 		=> "Hi, I\'m just a text input - nr 2",
						"fold" 		=> "switch_ex4", /* the switch hook */
						"type" 		=> "text"
				);
				
				
$of_options[] = array( 	"name" 		=> "Homepage Layout Manager",
						"desc" 		=> "Organize how you want the layout to appear on the homepage",
						"id" 		=> "homepage_blocks",
						"std" 		=> $of_options_homepage_blocks,
						"type" 		=> "sorter"
				);
					
$of_options[] = array( 	"name" 		=> "Slider Options",
						"desc" 		=> "Unlimited slider with drag and drop sortings.",
						"id" 		=> "pingu_slider",
						"std" 		=> "",
						"type" 		=> "slider"
				);
					
$of_options[] = array( 	"name" 		=> "Background Images",
						"desc" 		=> "Select a background pattern.",
						"id" 		=> "custom_bg",
						"std" 		=> $bg_images_url."bg0.png",
						"type" 		=> "tiles",
						"options" 	=> $bg_images,
				);
					
$of_options[] = array( 	"name" 		=> "Typography",
						"desc" 		=> "Typography option with each property can be called individually.",
						"id" 		=> "custom_type",
						"std" 		=> array('size' => '12px','style' => 'bold italic'),
						"type" 		=> "typography"
				);

$of_options[] = array( 	"name" 		=> "General Settings",
						"type" 		=> "heading"
				);
					
$url =  ADMIN_DIR . 'assets/images/';
$of_options[] = array( 	"name" 		=> "Main Layout",
						"desc" 		=> "Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.",
						"id" 		=> "layout",
						"std" 		=> "2c-l-fixed.css",
						"type" 		=> "images",
						"options" 	=> array(
							'1col-fixed.css' 	=> $url . '1col.png',
							'2c-r-fixed.css' 	=> $url . '2cr.png',
							'2c-l-fixed.css' 	=> $url . '2cl.png',
							'3c-fixed.css' 		=> $url . '3cm.png',
							'3c-r-fixed.css' 	=> $url . '3cr.png'
						)
				);
				
$of_options[] = array( 	"name" 		=> "Tracking Code",
						"desc" 		=> "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
						"id" 		=> "google_analytics",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> "Footer Text",
						"desc" 		=> "You can use the following shortcodes in your footer text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year]",
						"id" 		=> "footer_text",
						"std" 		=> "Powered by [wp-link]. Built on the [theme-link].",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> "Styling Options",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Theme Stylesheet",
						"desc" 		=> "Select your themes alternative color scheme.",
						"id" 		=> "alt_stylesheet",
						"std" 		=> "default.css",
						"type" 		=> "select",
						"options" 	=> $alt_stylesheets
				);
				
$of_options[] = array( 	"name" 		=> "Body Background Color",
						"desc" 		=> "Pick a background color for the theme (default: #fff).",
						"id" 		=> "body_background",
						"std" 		=> "",
						"type" 		=> "color"
				);
				
$of_options[] = array( 	"name" 		=> "Header Background Color",
						"desc" 		=> "Pick a background color for the header (default: #fff).",
						"id" 		=> "header_background",
						"std" 		=> "",
						"type" 		=> "color"
				);
				
$of_options[] = array( 	"name" 		=> "Footer Background Color",
						"desc" 		=> "Pick a background color for the footer (default: #fff).",
						"id" 		=> "footer_background",
						"std" 		=> "",
						"type" 		=> "color"
				);
				
$of_options[] = array( 	"name" 		=> "Body Font",
						"desc" 		=> "Specify the body font properties",
						"id" 		=> "body_font",
						"std" 		=> array('size' => '12px','face' => 'arial','style' => 'normal','color' => '#000000'),
						"type" 		=> "typography"
				);  
				
$of_options[] = array( 	"name" 		=> "Custom CSS",
						"desc" 		=> "Quickly add some CSS to your theme by adding it to this block.",
						"id" 		=> "custom_css",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> "Example Options",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Typography",
						"desc" 		=> "This is a typographic specific option.",
						"id" 		=> "typography",
						"std" 		=> array(
											'size'  => '12px',
											'face'  => 'verdana',
											'style' => 'bold italic',
											'color' => '#123456'
										),
						"type" 		=> "typography"
				);  
				
$of_options[] = array( 	"name" 		=> "Border",
						"desc" 		=> "This is a border specific option.",
						"id" 		=> "border",
						"std" 		=> array(
											'width' => '2',
											'style' => 'dotted',
											'color' => '#444444'
										),
						"type" 		=> "border"
				);
				
$of_options[] = array( 	"name" 		=> "Colorpicker",
						"desc" 		=> "No color selected.",
						"id" 		=> "example_colorpicker",
						"std" 		=> "",
						"type" 		=> "color"
					); 
					
$of_options[] = array( 	"name" 		=> "Colorpicker (default #2098a8)",
						"desc" 		=> "Color selected.",
						"id" 		=> "example_colorpicker_2",
						"std" 		=> "#2098a8",
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> "Input Text",
						"desc" 		=> "A text input field.",
						"id" 		=> "test_text",
						"std" 		=> "Default Value",
						"type" 		=> "text"
				);
				
$of_options[] = array( 	"name" 		=> "Input Checkbox (false)",
						"desc" 		=> "Example checkbox with false selected.",
						"id" 		=> "example_checkbox_false",
						"std" 		=> 0,
						"type" 		=> "checkbox"
				);
				
$of_options[] = array( 	"name" 		=> "Input Checkbox (true)",
						"desc" 		=> "Example checkbox with true selected.",
						"id" 		=> "example_checkbox_true",
						"std" 		=> 1,
						"type" 		=> "checkbox"
				);
				
$of_options[] = array( 	"name" 		=> "Normal Select",
						"desc" 		=> "Normal Select Box.",
						"id" 		=> "example_select",
						"std" 		=> "three",
						"type" 		=> "select",
						"options" 	=> $of_options_select
				);
				
$of_options[] = array( 	"name" 		=> "Mini Select",
						"desc" 		=> "A mini select box.",
						"id" 		=> "example_select_2",
						"std" 		=> "two",
						"type" 		=> "select",
						"mod" 		=> "mini",
						"options" 	=> $of_options_radio
				); 
				
$of_options[] = array( 	"name" 		=> "Google Font Select",
						"desc" 		=> "Some description. Note that this is a custom text added added from options file.",
						"id" 		=> "g_select",
						"std" 		=> "Select a font",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "This is my preview text!", //this is the text from preview box
										"size" => "30px" //this is the text size from preview box
						),
						"options" 	=> array(
										"none" => "Select a font",//please, always use this key: "none"
										"Lato" => "Lato",
										"Loved by the King" => "Loved By the King",
										"Tangerine" => "Tangerine",
										"Terminal Dosis" => "Terminal Dosis"
						)
				);
				
$of_options[] = array( 	"name" 		=> "Google Font Select2",
						"desc" 		=> "Some description.",
						"id" 		=> "g_select2",
						"std" 		=> "Select a font",
						"type" 		=> "select_google_font",
						"options" 	=> array(
										"none" => "Select a font",//please, always use this key: "none"
										"Lato" => "Lato",
										"Loved by the King" => "Loved By the King",
										"Tangerine" => "Tangerine",
										"Terminal Dosis" => "Terminal Dosis"
									)
				);
				
$of_options[] = array( 	"name" 		=> "Input Radio (one)",
						"desc" 		=> "Radio select with default of 'one'.",
						"id" 		=> "example_radio",
						"std" 		=> "one",
						"type" 		=> "radio",
						"options" 	=> $of_options_radio
				);
				
$url =  ADMIN_DIR . 'assets/images/';
$of_options[] = array( 	"name" 		=> "Image Select",
						"desc" 		=> "Use radio buttons as images.",
						"id" 		=> "images",
						"std" 		=> "warning.css",
						"type" 		=> "images",
						"options" 	=> array(
											'warning.css' 	=> $url . 'warning.png',
											'accept.css' 	=> $url . 'accept.png',
											'wrench.css' 	=> $url . 'wrench.png'
										)
				);
				
$of_options[] = array( 	"name" 		=> "Textarea",
						"desc" 		=> "Textarea description.",
						"id" 		=> "example_textarea",
						"std" 		=> "Default Text",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> "Multicheck",
						"desc" 		=> "Multicheck description.",
						"id" 		=> "example_multicheck",
						"std" 		=> array("three","two"),
						"type" 		=> "multicheck",
						"options" 	=> $of_options_radio
				);
				
$of_options[] = array( 	"name" 		=> "Select a Category",
						"desc" 		=> "A list of all the categories being used on the site.",
						"id" 		=> "example_category",
						"std" 		=> "Select a category:",
						"type" 		=> "select",
						"options" 	=> $of_categories
				);
				
//Advanced Settings
$of_options[] = array( 	"name" 		=> "Advanced Settings",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Folding Checkbox",
						"desc" 		=> "This checkbox will hide/show a couple of options group. Try it out!",
						"id" 		=> "offline",
						"std" 		=> 0,
						"folds" 	=> 1,
						"type" 		=> "checkbox"
				);
				
$of_options[] = array( 	"name" 		=> "Hidden option 1",
						"desc" 		=> "This is a sample hidden option 1",
						"id" 		=> "hidden_option_1",
						"std" 		=> "Hi, I\'m just a text input",
						"fold" 		=> "offline", /* the checkbox hook */
						"type" 		=> "text"
				);
				
$of_options[] = array( 	"name" 		=> "Hidden option 2",
						"desc" 		=> "This is a sample hidden option 2",
						"id" 		=> "hidden_option_2",
						"std" 		=> "Hi, I\'m just a text input",
						"fold" 		=> "offline", /* the checkbox hook */
						"type" 		=> "text"
				);
				
$of_options[] = array( 	"name" 		=> "Hello there!",
						"desc" 		=> "",
						"id" 		=> "introduction_2",
						"std" 		=> "<h3 style=\"margin: 0 0 10px;\">Grouped Options.</h3>
						You can group a bunch of options under a single heading by removing the 'name' value from the options array except for the first option in the group.",
						"icon" 		=> true,
						"type" 		=> "info"
				);
				
				$of_options[] = array( 	"name" 		=> "Some pretty colors for you",
										"desc" 		=> "Color 1.",
										"id" 		=> "example_colorpicker_3",
										"std" 		=> "#2098a8",
										"type" 		=> "color"
								);
								
				$of_options[] = array( 	"name" 		=> "",
										"desc" 		=> "Color 2.",
										"id" 		=> "example_colorpicker_4",
										"std" 		=> "#2098a8",
										"type" 		=> "color"
								);
								
				$of_options[] = array( 	"name" 		=> "",
										"desc" 		=> "Color 3.",
										"id" 		=> "example_colorpicker_5",
										"std" 		=> "#2098a8",
										"type" 		=> "color"
								);
								
				$of_options[] = array( 	"name" 		=> "",
										"desc" 		=> "Color 4.",
										"id" 		=> "example_colorpicker_6",
										"std" 		=> "#2098a8",
										"type" 		=> "color"
								);
				
// Backup Options
$of_options[] = array( 	"name" 		=> "Backup Options",
						"type" 		=> "heading",
						"icon"		=> ADMIN_IMAGES . "icon-slider.png"
				);
				
$of_options[] = array( 	"name" 		=> "Backup and Restore Options",
						"id" 		=> "of_backup",
						"std" 		=> "",
						"type" 		=> "backup",
						"desc" 		=> 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
				);
				
$of_options[] = array( 	"name" 		=> "Transfer Theme Options Data",
						"id" 		=> "of_transfer",
						"std" 		=> "",
						"type" 		=> "transfer",
						"desc" 		=> 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".',
				);
				
	}//End function: of_options()
}//End chack if function exists: of_options()
?>
