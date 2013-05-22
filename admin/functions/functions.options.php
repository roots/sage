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

// General Options
$of_options[] = array(
  "name"      => "General",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Logo",
  "desc"      => "Upload a logo image using the media uploader, or define the URL directly. Use the shortcodes [site_url] or [site_url_secure] for setting default URLs",
  "id"        => "logo",
  "std"       => "",
  "type"      => "media"
);

$of_options[] = array(
  "name"      => "No gradients - \"Flat\" look.",
  "desc"      => "This option will disable all gradients in your site, giving it a cleaner look. Default: OFF.",
  "id"        => "general_flat",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Border-Radius",
  "desc"      => "You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4",
  "id"        => "layout_secondary_width",
  "std"       => 4,
  "min"       => 0,
  "step"      => 1,
  "max"       => 50,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Featured Images on Archives",
  "desc"      => "Display featured Images on post archives (such as categories, tags, month view etc). Default: OFF.",
  "id"        => "feat_img_archive",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Archives Featured Image Width",
  "desc"      => "Select the width of your featured images on post archives. Default: 550px",
  "id"        => "feat_img_archive_width",
  "std"       => 550,
  "min"       => 100,
  "step"      => 1,
  "max"       => 1600,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Archives Featured Image Height",
  "desc"      => "Select the height of your featured images on post archives. Default: 300px",
  "id"        => "feat_img_archive_height",
  "std"       => 300,
  "min"       => 50,
  "step"      => 1,
  "max"       => 1000,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Featured Images on Posts",
  "desc"      => "Display featured Images on posts. Default: OFF.",
  "id"        => "feat_img_post",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Posts Featured Image Width",
  "desc"      => "Select the width of your featured images on single posts. Default: 550px",
  "id"        => "feat_img_post_width",
  "std"       => 550,
  "min"       => 100,
  "step"      => 1,
  "max"       => 1600,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Posts Featured Image Height",
  "desc"      => "Select the height of your featured images on single posts. Default: 300px",
  "id"        => "feat_img_post_height",
  "std"       => 300,
  "min"       => 50,
  "step"      => 1,
  "max"       => 1000,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

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
  "less"      => true,
  "customizer"=> array(),
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
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Fluid Layout",
  "desc"      => "OFF by default. If you turn this ON, then the layout of your site will become fluid, spanning accross the whole width of your screen.",
  "id"        => "fluid",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Primary Sidebar Width",
  "desc"      => "Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 (4/12) of the total page width.",
  "id"        => "layout_primary_width",
  "std"       => 4,
  "min"       => 2,
  "step"      => 1,
  "max"       => 6,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Secondary Sidebar Width",
  "desc"      => "Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 (4/12) of the total page width.",
  "id"        => "layout_secondary_width",
  "std"       => 3,
  "min"       => 2,
  "step"      => 1,
  "max"       => 4,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Tiny Screen Width",
  "desc"      => "The width of Tiny screens. This is used to calculate the responsive layout breakpoints. Suitable for phones. Default: 480px",
  "id"        => "layout_screen_tiny",
  "std"       => 480,
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Small Screen Width",
  "desc"      => "The width of Small screens. This is used to calculate the responsive layout breakpoints. Suitable for tablets and small screens. Default: 768px",
  "id"        => "layout_screen_small",
  "std"       => 768,
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui",

);

$of_options[] = array(
  "name"      => "Medium Screen Width",
  "desc"      => "The width of Normal screens. This is used to calculate the responsive layout breakpoints. Suitable for medium screens. Default: 992px",
  "id"        => "layout_screen_medium",
  "std"       => 992,
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Large Screen Width",
  "desc"      => "The width of Large screens. This is used to calculate the responsive layout breakpoints. Suitable for large screens. Default: 1200px",
  "id"        => "layout_screen_large",
  "std"       => 1200,
  "min"       => 320,
  "step"      => 2,
  "max"       => 1600,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

$of_options[] = array(
  "name"      => "Columns Gutter",
  "desc"      => "The space between the columns in your grid. Default: 30px",
  "id"        => "layout_gutter",
  "std"       => 30,
  "min"       => 0,
  "step"      => 2,
  "max"       => 100,
  "advanced"  => true,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

// Colors
$of_options[] = array(
  "name"      => "Site Colors",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Background Color",
  "desc"      => "Pick a background color for your site. Default: #ffffff.",
  "id"        => "color_body_bg",
  "std"       => "#ffffff",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Text Color",
  "desc"      => "Pick a color for your site's main text. Default: #333333.",
  "id"        => "color_text",
  "std"       => "#333333",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Links Color",
  "desc"      => "Pick a color for your site's links. Default: #428bca.",
  "id"        => "color_links",
  "std"       => "#428bca",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Brand Colors: Primary",
  "desc"      => "Select your primary branding color. This will affect various areas of your site, including the color of your primary buttons, the background of some elements and many more. Default: #428bca.",
  "id"        => "color_brand_primary",
  "std"       => "#428bca",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Brand Colors: Success",
  "desc"      => "Select your branding color for success messages etc. Default: #5cb85c.",
  "id"        => "color_brand_success",
  "std"       => "#5cb85c",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Brand Colors: Warning",
  "desc"      => "Select your branding color for warning messages etc. Default: #f0ad4e.",
  "id"        => "color_brand_warning",
  "std"       => "#f0ad4e",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Brand Colors: Danger",
  "desc"      => "Select your branding color for success messages etc. Default: #d9534f.",
  "id"        => "color_brand_danger",
  "std"       => "#d9534f",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Brand Colors: Info",
  "desc"      => "Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \"info\" class. Default: #5bc0de.",
  "id"        => "color_brand_info",
  "std"       => "#5bc0de",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
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
  "std"       => 1,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Display Branding (Sitename or Logo)",
  "desc"      => "Default: ON",
  "id"        => "navbar_brand",
  "std"       => 1,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Use Logo (if available) for branding",
  "desc"      => "If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON",
  "id"        => "navbar_logo",
  "std"       => 1,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "NavBar Background Color",
  "desc"      => "Pick a background color for the NavBar. Default: #eeeeee.",
  "id"        => "navbar_bg",
  "std"       => "#eeeeee",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "NavBar Text Color",
  "desc"      => "Pick a color for the NavBar text. This applies to menu items and the Sitename (if no logo is uploaded). Default: #777777.",
  "id"        => "navbar_color",
  "std"       => "#777777",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Display social links in the Navbar.",
  "desc"      => "Display social links in the Navbar. These can be setup in the \"Social\" section on the left. Default: OFF",
  "id"        => "navbar_social",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Search",
  "desc"      => "Display a search form in the Navbar. Default: OFF",
  "id"        => "navbar_search",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Float menu to the right",
  "desc"      => "Floats the primary navigation to the right. Default: OFF",
  "id"        => "navbar_nav_right",
  "std"       => 0,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "NavBar Positioning",
  "desc"      => "Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you're using one of the \"fixed\" options, the navbar will stay fixed on the top or bottom of the page. Default: Normal",
  "id"        => "navbar_position",
  "std"       => 0,
  "type"      => "select",
  "less"      => true,
  "customizer"=> array(),
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
  "std"       => 50,
  "min"       => 10,
  "step"      => 1,
  "max"       => 600,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "sliderui"
);

// TODO: Make this a dropdown or an image control so that people can select among more than 1 styles.
$of_options[] = array(
  "name"      => "Alternative style for NavBars",
  "desc"      => "You can use an alternative menu style for your NavBars. OFF by default. ",
  "id"        => "navbar_altmenu",
  "std"       => 0,
  "less"      => true,
  "customizer"=> array(),
  "type"      => "switch"
);

// Jumbotron (Hero)
$of_options[] = array(
  "name"      => "Jumbotron",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Jumbotron Background Color",
  "desc"      => "Select the background color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #EEEEEE.",
  "id"        => "jumbotron_bg",
  "std"       => "#EEEEEE",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Background Image",
  "desc"      => "Upload a logo image using the media uploader, or define the URL directly. Use the shortcodes [site_url] or [site_url_secure] for setting default URLs",
  "id"        => "jumbotron_bg_img",
  "std"       => "",
  "type"      => "media"
);

$of_options[] = array(
  "name"      => "Background Repeat",
  "desc"      => "Select how (or if) the selected background should be tiled. Default: Tile",
  "id"        => "jumbotron_bg_repeat",
  "std"       => "repeat",
  "type"      => "radio",
  "options"   => array(
    'no-repeat'  => __( 'No Repeat', 'shoestrap' ),
    'repeat'     => __( 'Tile', 'shoestrap' ),
    'repeat-x'   => __( 'Tile Horizontally', 'shoestrap' ),
    'repeat-y'   => __( 'Tile Vertically', 'shoestrap' ),
  ),
);

$of_options[] = array(
  "name"      => "Background Alignment",
  "desc"      => "Select how the selected background should be horizontally aligned. Default: Left",
  "id"        => "jumbotron_bg_pos_x",
  "std"       => "repeat",
  "type"      => "radio",
  "options"   => array(
    'left'    => __( 'Left', 'shoestrap' ),
    'right'   => __( 'Right', 'shoestrap' ),
    'center'  => __( 'Center', 'shoestrap' ),
  ),
);

$of_options[] = array(
  "name"      => "Jumbotron Color",
  "desc"      => "Select the text color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #333333.",
  "id"        => "jumbotron_color",
  "std"       => "#333333",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Display Jumbotron only on the Frontpage.",
  "desc"      => "When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON",
  "id"        => "jumbotron_visibility",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 1,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Full-Width",
  "desc"      => "When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.",
  "id"        => "jumbotron_nocontainer",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 1,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Use fittext script for the title.",
  "desc"      => "Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF",
  "id"        => "jumbotron_title_fit",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Center-align the content.",
  "desc"      => "Turn this on to center-align the contents of the Jumbotron area. Default: OFF",
  "id"        => "jumbotron_title_fit",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

// Header
$of_options[] = array(
  "name"      => "Header",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Display the Header.",
  "desc"      => "Turn this ON to display the header. Default: OFF",
  "id"        => "header_toggle",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 0,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Display branding on your Header.",
  "desc"      => "Turn this ON to display branding (Sitename or Logo)on your Header. Default: ON",
  "id"        => "header_branding",
  "less"      => true,
  "customizer"=> array(),
  "std"       => 1,
  "type"      => "switch"
);

$of_options[] = array(
  "name"      => "Header Background Color",
  "desc"      => "Select the background color for your header. Default: #EEEEEE.",
  "id"        => "header_bg",
  "std"       => "#EEEEEE",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Header Text Color",
  "desc"      => "Select the text color for your header. Default: #333333.",
  "id"        => "header_color",
  "std"       => "#333333",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

// Footer
$of_options[] = array(
  "name"      => "Footer",
  "type"      => "heading"
);

$of_options[] = array(
  "name"      => "Footer Background Color",
  "desc"      => "Select the background color for your footer. Default: #ffffff.",
  "id"        => "footer_bg",
  "std"       => "#ffffff",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Footer Text Color",
  "desc"      => "Select the text color for your footer. Default: #333333.",
  "id"        => "footer_color",
  "std"       => "#333333",
  "less"      => true,
  "customizer"=> array(),
  "type"      => "color"
);

$of_options[] = array(
  "name"      => "Footer Text",
  "desc"      => "The text that will be displayed in your footer. Default: your site's name.",
  "id"        => "footer_text",
  "std"       => get_bloginfo( 'name' ),
  "type"      => "text"
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