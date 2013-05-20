<?php

add_action('init','of_options');
if (!function_exists('of_options')) {
  function of_options() {
    //Access the WordPress Categories via an Array
    $of_categories    = array();
    $of_categories_obj  = get_categories('hide_empty=0');
    foreach ($of_categories_obj as $of_cat) {
        $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
    $categories_tmp   = array_unshift($of_categories, "Select a category:");

    //Access the WordPress Pages via an Array
    $of_pages       = array();
    $of_pages_obj     = get_pages('sort_column=post_parent,menu_order');
    foreach ($of_pages_obj as $of_page) {
        $of_pages[$of_page->ID] = $of_page->post_name; }
    $of_pages_tmp     = array_unshift($of_pages, "Select a page:");

    //Testing 
    $of_options_select  = array("one","two","three","four","five");
    $of_options_radio   = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");

    //Sample Homepage blocks for the layout manager (sorter)
    $of_options_homepage_blocks = array
    ( 
      "disabled" => array (
        "placebo"     => "placebo", //REQUIRED!
        "block_one"   => "Block One",
        "block_two"   => "Block Two",
        "block_three" => "Block Three",
      ), 
      "enabled" => array (
        "placebo"     => "placebo", //REQUIRED!
        "block_four"  => "Block Four",
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
    $bg_images_path = STYLESHEETPATH. '/images/bg/'; // change this to where you store your bg images
    $bg_images_url = get_bloginfo('template_url').'/images/bg/'; // change this to where you store your bg images
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
    $uploads_arr    = wp_upload_dir();
    $all_uploads_path   = $uploads_arr['path'];
    $all_uploads    = get_option('of_uploads');
    $other_entries    = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
    $body_repeat    = array("no-repeat","repeat-x","repeat-y","repeat");
    $body_pos       = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

    // Image Alignment radio box
    $of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

    // Image Links to Options
    $of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;

// Layout Settings

$of_options[] = array(
  "name"      => "Layout Settings",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Main Layout",
  "desc"      => "Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.",
  "id"        => "layout",
  "std"       => get_theme_mod('layout', 1),
  "type"      => "images",
  "options"   => array(
    0         => get_template_directory_uri() . '/assets/img/m.png',
    1         => get_template_directory_uri() . '/assets/img/mp.png',
    2         => get_template_directory_uri() . '/assets/img/pm.png',
    3         => get_template_directory_uri() . '/assets/img/psm.png',
    4         => get_template_directory_uri() . '/assets/img/mps.png',
    5         => get_template_directory_uri() . '/assets/img/pms.png',
  )
);

$of_options[] = array(
  "name"      => "Show sidebars on the frontpage",
  "desc"      => "OFF by default. If you want to display the sidebars in your frontpage, turn this ON.",
  "id"        => "layout_sidebar_on_front",
  "std"       => get_theme_mod('layout_sidebar_on_front', 0 ),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "Primary Sidebar Width",
  "desc"      => "Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 (4/12) of the total page width.",
  "id"        => "layout_primary_width",
  "std"       => get_theme_mod('layout_primary_width', 4),
  "min"       => 2,
  "step"      => 1,
  "max"       => 6,
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Secondary Sidebar Width",
  "desc"      => "Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 (4/12) of the total page width.",
  "id"        => "layout_secondary_width",
  "std"       => get_theme_mod('layout_secondary_width', 3),
  "min"       => 2,
  "step"      => 1,
  "max"       => 4,
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Tiny Screen Width",
  "desc"      => "The width of Tiny screens. This is used to calculate the responsive layout breakpoints. Suitable for phones. Default: 480px",
  "id"        => "layout_screen_tiny",
  "std"       => get_theme_mod('layout_screen_tiny', 480),
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Small Screen Width",
  "desc"      => "The width of Small screens. This is used to calculate the responsive layout breakpoints. Suitable for tablets and small screens. Default: 768px",
  "id"        => "layout_screen_small",
  "std"       => get_theme_mod('layout_screen_small', 768),
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Medium Screen Width",
  "desc"      => "The width of Normal screens. This is used to calculate the responsive layout breakpoints. Suitable for medium screens. Default: 992px",
  "id"        => "layout_screen_medium",
  "std"       => get_theme_mod('layout_screen_medium', 992),
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Large Screen Width",
  "desc"      => "The width of Large screens. This is used to calculate the responsive layout breakpoints. Suitable for large screens. Default: 1200px",
  "id"        => "layout_screen_large",
  "std"       => get_theme_mod('layout_screen_large', 1200),
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "type"      => "sliderui"
);

// NavBar Settings

$of_options[] = array(
  "name"      => "NavBar Settings",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Show the Main NavBar",
  "desc"      => "ON by default. If you want to hide your main navbar you can do it here. When you do, the main menu will still be displayed but not styled as a navbar. If you want to completely disable it, then please visit the customizer and on the \"Navigation\" section, select \"None\".",
  "id"        => "navbar_toggle",
  "std"       => get_theme_mod('navbar_toggle', 1),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "Display Branding (Sitename or Logo)",
  "desc"      => "Default: ON",
  "id"        => "navbar_brand",
  "std"       => get_theme_mod('navbar_brand', 1),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "Use Logo (if available) for branding",
  "desc"      => "If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON",
  "id"        => "navbar_logo",
  "std"       => get_theme_mod('navbar_logo', 1),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "NavBar Background Color",
  "desc"      => "Pick a background color for the NavBar. Default: #eeeeee.",
  "id"        => "navbar_bg",
  "std"       => get_theme_mod('navbar_bg'),
  "type"      => "color"
);        

$of_options[] = array(
  "name"      => "NavBar Text Color",
  "desc"      => "Pick a color for the NavBar text. This applies to menu items and the Sitename (if no logo is uploaded). Default: #777777.",
  "id"        => "navbar_color",
  "std"       => get_theme_mod('navbar_color'),
  "type"      => "color"
);        

$of_options[] = array(
  "name"      => "Display social links in the Navbar.",
  "desc"      => "Display social links in the Navbar. These can be setup in the \"Social\" section on the left. Default: OFF",
  "id"        => "navbar_social",
  "std"       => get_theme_mod('navbar_social', 0),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "Search",
  "desc"      => "Display a search form in the Navbar. Default: OFF",
  "id"        => "navbar_search",
  "std"       => get_theme_mod('navbar_search', 0),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "Float menu to the right",
  "desc"      => "Floats the primary navigation to the right. Default: OFF",
  "id"        => "navbar_nav_right",
  "std"       => get_theme_mod('navbar_nav_right', 0),
  "type"      => "switch"
);   

$of_options[] = array(
  "name"      => "NavBar Positioning",
  "desc"      => "Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you're using one of the \"fixed\" options, the navbar will stay fixed on the top or bottom of the page. Default: Normal",
  "id"        => "navbar_position",
  "std"       => get_theme_mod('navbar_position', 0),
  "type"      => "select",
  "options"   => array(
    0         => __( 'Normal', 'shoestrap' ),
    1         => __( 'Fixed to Top', 'shoestrap' ),
    2         => __( 'Fixed to Bottom', 'shoestrap' )
  )
);   

$of_options[] = array(
  "name"      => "Navbar Height",
  "desc"      => "Select the height of the Navbar. If you're using a logo then this should be equal or greater than its height.",
  "id"        => "navbar_height",
  "std"       => get_theme_mod('navbar_height', 50),
  "min"       => 10,
  "step"      => 1,
  "max"       => 600,
  "type"      => "sliderui"
);



// Backup Options
$of_options[] = array(  "name"    => "Backup Options",
            "type"    => "heading"
        );
        
$of_options[] = array(  "name"    => "Backup and Restore Options",
            "id"    => "of_backup",
            "std"     => "",
            "type"    => "backup",
            "desc"    => 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
        );
        
$of_options[] = array(  "name"    => "Transfer Theme Options Data",
            "id"    => "of_transfer",
            "std"     => "",
            "type"    => "transfer",
            "desc"    => 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".',
        );
        
  }
}