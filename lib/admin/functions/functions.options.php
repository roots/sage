<?php

remove_action('init','of_options');
add_action('init','of_options_shoestrap');

if (!function_exists('of_options_shoestrap')) {
  function of_options_shoestrap() {
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
    $bg_images_path = get_template_directory() . '/lib/assets/img/backgrounds';
    $bg_images_url = get_bloginfo('template_url').'/lib/assets/img/backgrounds/';
    $bg_images = array();

    if ( is_dir($bg_images_path) ) {
      if ( $bg_images_dir = opendir( $bg_images_path ) ) {
        while ( ( $bg_images_file = readdir( $bg_images_dir ) ) !== false ) {
          if( stristr( $bg_images_file, ".png" ) !== false || stristr( $bg_images_file, ".jpg" ) !== false) {
            $bg_images[] = $bg_images_url . $bg_images_file;
          }
        }
      }
    }


    //Background Patterns Reader
    $bg_pattern_images_path = get_template_directory() . '/lib/assets/img/patterns';
    $bg_pattern_images_url = get_bloginfo('template_url').'/lib/assets/img/patterns/';
    $bg_pattern_images = array();

    if ( is_dir($bg_pattern_images_path) ) {
      if ( $bg_pattern_images_dir = opendir( $bg_pattern_images_path ) ) {
        while ( ( $bg_pattern_images_file = readdir( $bg_pattern_images_dir ) ) !== false ) {
          if( stristr( $bg_pattern_images_file, ".png" ) !== false || stristr( $bg_pattern_images_file, ".jpg" ) !== false) {
            $bg_pattern_images[] = $bg_pattern_images_url . $bg_pattern_images_file;
          }
        }
      }
    }

    //Preset Styles Reader
    $preset_styles_path = get_template_directory() . '/lib/admin/presets';

    $preset_styles_url = get_bloginfo('template_url').'/lib/admin/presets/';
    $preset_styles = array();

    if ( is_dir($preset_styles_path) ) {

      if ( $preset_styles_dir = opendir( $preset_styles_path ) ) {
        while ( ( $preset_styles_file = readdir( $preset_styles_dir ) ) !== false ) {

          if( stristr( $preset_styles_file, ".txt" ) !== false) {
            $array = array();
            $pre = $preset_styles_url . $preset_styles_file;
            $explode = explode("/", $pre);
            $style = end($explode);
            $key = explode('.',$style);
            $preset_styles[$key[0]]['style'] = $style;
          }
          if( stristr( $preset_styles_file, ".png" ) !== false || stristr( $preset_styles_file, ".jpg" ) !== false) {
            $preview = $preset_styles_url . $preset_styles_file;
            $preview = explode("/", $preview);
            $preview = end($preview);

            $key = explode('.',$preview);
            $preset_styles[$key[0]]['preview'] = $preview;
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
    global $of_options, $smof_details;

    $of_options = array();

    // General Options
    $of_options[] = array(
      "name"      => __("General", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help1",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">General theme Options</h3>
                      <p>In this section you can define some basic options for your theme.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("No gradients - \"Flat\" look.", "shoestrap"),
      "desc"      => __("This option will disable all gradients in your site, giving it a cleaner look. Default: OFF.", "shoestrap"),
      "id"        => "general_flat",
      "less"      => true,
      "std"       => 0,
      "type"      => "switch",
    );

    $of_options[] = array(
      "name"      => __("Google Analytics ID", "shoestrap"),
      "desc"      => __("Paste your Google Analytics ID here to enable analytics tracking. Your user ID should be in the form of UA-XXXXX-Y.", "shoestrap"),
      "id"        => "analytics_id",
      "std"       => 0,
      "type"      => "text",
    );

    $of_options[] = array(
      "name"      => __("Allow comments on pages TODO", "shoestrap"),
      "desc"      => __("Allow comments on regular pages.", "shoestrap"),
      "id"        => "tracking_code",
      "std"       => 0,
      "type"      => "checkbox",
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help2",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Border-Radius and Padding Base</h3>
                      <p>These 2 settings affect varius areas of your site, most notably buttons.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Border-Radius", "shoestrap"),
      "desc"      => __("You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4", "shoestrap"),
      "id"        => "general_border_radius",
      "std"       => 4,
      "min"       => 0,
      "step"      => 1,
      "max"       => 50,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Padding Base", "shoestrap"),
      "desc"      => __("You can adjust the padding base. This affects buttons size and lots of other cool stuff too! Default: 8", "shoestrap"),
      "id"        => "padding_base",
      "std"       => 8,
      "min"       => 0,
      "step"      => 1,
      "max"       => 20,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );

// Branding Options
    $of_options[] = array(
      "name"      => __("Branding Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help6",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Colors</h3>
                      <p>The primary color you select will also affect other elements on your site,
                      such as table borders, widgets colors, input elements, dropdowns etc.
                      The branding colors you select will be used throughout the site in various elements.
                      One of the most important settings in your branding is your primary color,
                      since this will be used more often.</p>
                      ",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Logo", "shoestrap"),
      "desc"      => __("Upload a logo image using the media uploader, or define the URL directly.", "shoestrap"),
      "id"        => "logo",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Upload Retina Logo TODO", "shoestrap"),
      "desc"      => __("By enabling your site can be retina ready. Requires a logo re-uploaded at 2x the size desired. Default: Off", "shoestrap"),
      "id"        => "retina_logo_toggle",
      "std"       => "",
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "retina_help",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Retina Logo</h3>
                      <p>Upload a logo that is exactly 2x the size you want to typically display. A version will then be generated for general site use. If you have previously uploaded a logo, you will need to re-upload it to generate the proper versions.</p>",
      "icon"      => true,
      "fold"      => "retina_logo_toggle",
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Custom Favicon", "shoestrap"),
      "desc"      => __("You can put url of an ico image that will represent your website's favicon (32px x 32px)", "shoestrap"),
      "id"        => "favicon",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Apple Icon", "shoestrap"),
      "desc"      => __("This will create icons for Apple iPhone (57px x 57px), Apple iPhone Retina Version (114px x 114px), Apple iPad (72px x 72px) and Apple iPad Retina (144px x 144px). Please note that for better results the image you upload should be at least 144px x 144px.", "shoestrap"),
      "id"        => "apple_icon",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Primary Color", "shoestrap"),
      "desc"      => __("Pick a background color for your site. Default: #ffffff.", "shoestrap"),
      "id"        => "color_body_bg",
      "std"       => "#ffffff",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Text Color", "shoestrap"),
      "desc"      => __("Pick a color for your site's main text. Default: #333333.", "shoestrap"),
      "id"        => "color_text",
      "std"       => "#333333",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Links Color", "shoestrap"),
      "desc"      => __("Pick a color for your site's links. Default: #428bca.", "shoestrap"),
      "id"        => "color_links",
      "std"       => "#428bca",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Brand Colors: Primary", "shoestrap"),
      "desc"      => __("Select your primary branding color. This will affect various areas of your site, including the color of your primary buttons, the background of some elements and many more. Default: #428bca.", "shoestrap"),
      "id"        => "color_brand_primary",
      "std"       => "#428bca",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Brand Colors: Success", "shoestrap"),
      "desc"      => __("Select your branding color for success messages etc. Default: #5cb85c.", "shoestrap"),
      "id"        => "color_brand_success",
      "std"       => "#5cb85c",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Brand Colors: Warning", "shoestrap"),
      "desc"      => __("Select your branding color for warning messages etc. Default: #f0ad4e.", "shoestrap"),
      "id"        => "color_brand_warning",
      "std"       => "#f0ad4e",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Brand Colors: Danger", "shoestrap"),
      "desc"      => __("Select your branding color for success messages etc. Default: #d9534f.", "shoestrap"),
      "id"        => "color_brand_danger",
      "std"       => "#d9534f",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Brand Colors: Info", "shoestrap"),
      "desc"      => __("Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \"info\" class. Default: #5bc0de.", "shoestrap"),
      "id"        => "color_brand_info",
      "std"       => "#5bc0de",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );



    // Background Options
    $of_options[] = array(
      "name"      => __("Background Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help4",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Background Images</h3>
                      <p>If you want a background image, you can select one here.
                      You can either upload a custom image, or use one of our pre-defined image patterns.
                      If you both upload a custom image and select a pattern, your custom image will override the selected pattern.
                      Please note that the image only applies to the area on the right and left of the main content area,
                      to ensure better content readability. You can also set the background position to be fixed or scroll!</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Use a Background Image", "shoestrap"),
      "desc"      => __("Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.", "shoestrap"),
      "id"        => "background_image_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Upload a Custom Background Image", "shoestrap"),
      "desc"      => __("Upload a Custom Background image using the media uploader, or define the URL directly.", "shoestrap"),
      "id"        => "background_image",
      "fold"      => "background_image_toggle",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Background Image Positioning", "shoestrap"),
      "desc"      => __("Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.", "shoestrap"),
      "id"        => "background_image_position_toggle",
      "std"       => 0,
      "fold"      => "background_image_toggle",
      "on"        => __("Custom", "shoestrap"),
      "off"       => __("Full Width", "shoestrap"),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Background Repeat", "shoestrap"),
      "desc"      => __("Select how (or if) the selected background should be tiled. Default: Tile", "shoestrap"),
      "id"        => "background_repeat",
      "fold"      => "background_image_position_toggle",
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
      "name"      => __("Background Alignment", "shoestrap"),
      "desc"      => __("Select how the selected background should be horizontally aligned. Default: Left", "shoestrap"),
      "id"        => "background_position_x",
      "fold"      => "background_image_position_toggle",
      "std"       => "repeat",
      "type"      => "radio",
      "options"   => array(
        'left'    => __( 'Left', 'shoestrap' ),
        'right'   => __( 'Right', 'shoestrap' ),
        'center'  => __( 'Center', 'shoestrap' ),
      ),
    );

    $of_options[] = array(
      "name"      => __("Use a Background Pattern", "shoestrap"),
      "desc"      => __("Select one of the already existing Background Patterns. Default: OFF.", "shoestrap"),
      "id"        => "background_pattern_toggle",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Choose a Background Pattern", "shoestrap"),
      "desc"      => __("Select a background pattern.", "shoestrap"),
      "id"        => "background_pattern",
      "fold"      => "background_pattern_toggle",
      "std"       => "",
      "type"      => "tiles",
      "options"   => $bg_pattern_images,
    );

    $of_options[] = array(
      "name"      => __("Background position", "shoestrap"),
      "desc"      => __("Changes how the background image or pattern is displayed from scroll to fixed position. Default: Scroll.", "shoestrap"),
      "id"        => "background_fixed_toggle",
      "std"       => 0,
      "on"        => __("Fixed", "shoestrap"),
      "off"       => __("Scroll", "shoestrap"),
      "type"      => "switch"
    );

    // Layout Settings
    $of_options[] = array(
      "name"      => __("Layout Settings", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help5",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Layout Options</h3>
                      <p>In this area you can select your site's layout, the width of your sidebars,
                      as well as other, more advanced options.</p>",
      "icon"      => true,
      "type"      => "info"
    );


    $of_options[] = array(
      "name"      => __("Site Style", "shoestrap"),
      "desc"      => __("Select the default site layout. Default: Wide", "shoestrap"),
      "id"        => "site_style",
      "std"       => "wide",
      "type"      => "select",
      "customizer"=> array(),
      "options"   => array(
        'wide'    =>"Wide",
        'boxed'   =>"Boxed",
        'fluid'   =>"Fluid",
      )
    );

    $of_options[] = array(
      "name"      => __("Layout", "shoestrap"),
      "desc"      => __("Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.", "shoestrap"),
      "id"        => "layout",
      "std"       => get_theme_mod('layout', 1),
      "type"      => "images",
      "customizer"=> array(),
      "options"   => array(
        0         => get_template_directory_uri() . '/lib/admin/assets/images/1c.png',
        1         => get_template_directory_uri() . '/lib/admin/assets/images/2cr.png',
        2         => get_template_directory_uri() . '/lib/admin/assets/images/2cl.png',
        3         => get_template_directory_uri() . '/lib/admin/assets/images/3cl.png',
        4         => get_template_directory_uri() . '/lib/admin/assets/images/3cr.png',
        5         => get_template_directory_uri() . '/lib/admin/assets/images/3cm.png',
      )
    );

    $of_options[] = array(
      "name"      => __("Show sidebars on the frontpage", "shoestrap"),
      "desc"      => __("OFF by default. If you want to display the sidebars in your frontpage, turn this ON.", "shoestrap"),
      "id"        => "layout_sidebar_on_front",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

/*
    $of_options[] = array(
      "name"      => __("NavBar Positioning", "shoestrap"),
      "desc"      => __("Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you're using one of the \"fixed\" options, the navbar will stay fixed on the top or bottom of the page. Default: Normal", "shoestrap"),
      "id"        => "navbar_position",
      "fold"      => "navbar_toggle",
      "std"       => 0,
      "type"      => "select",
      "customizer"=> array(),
      "options"   => array(
        0         => __( 'Normal', 'shoestrap' ),
        1         => __( 'Fixed to Top', 'shoestrap' ),
        2         => __( 'Fixed to Bottom', 'shoestrap' ),
      )
    );
*/


    $of_options[] = array(
      "name"      => __("Margin from top (Works best in \"Boxed\" mode)", "shoestrap"),
      "desc"      => __("This will add a margin above the navbar. Useful if you've enabled the 'Boxed' mode above. Default: 0px", "shoestrap"),
      "id"        => "navbar_margin_top",
      "fold"      => "navbar_boxed",
      "std"       => 0,
      "min"       => 0,
      "step"      => 1,
      "max"       => 120,
      "advanced"  => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Primary Sidebar Width", "shoestrap"),
      "desc"      => __("Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 (4/12) of the total page width.", "shoestrap"),
      "id"        => "layout_primary_width",
      "std"       => 4,
      "min"       => 2,
      "step"      => 1,
      "max"       => 6,
      "advanced"  => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Secondary Sidebar Width", "shoestrap"),
      "desc"      => __("Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 (4/12) of the total page width.", "shoestrap"),
      "id"        => "layout_secondary_width",
      "std"       => 3,
      "min"       => 2,
      "step"      => 1,
      "max"       => 4,
      "advanced"  => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Custom Grid", "shoestrap"),
      "desc"      => "<strong>" . __("CAUTION:", "shoestrap") . "</strong> " . __("Only use this if you know what you are doing, as changing these values might break the way your site looks on some devices. The default settings should be fine for the vast majority of sites.", "shoestrap"),
      "id"        => "custom_grid",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Tiny Screen Width", "shoestrap"),
      "desc"      => __("The width of Tiny screens. This is used to calculate the responsive layout breakpoints. Suitable for phones. Default: 480px", "shoestrap"),
      "id"        => "layout_screen_tiny",
      "fold"      => "custom_grid",
      "std"       => 480,
      "min"       => 320,
      "step"      => 2,
      "max"       => 1600,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Small Screen Width", "shoestrap"),
      "desc"      => __("The width of Small screens. This is used to calculate the responsive layout breakpoints. Suitable for tablets and small screens. Default: 768px", "shoestrap"),
      "id"        => "layout_screen_small",
      "fold"      => "custom_grid",
      "std"       => 768,
      "min"       => 320,
      "step"      => 2,
      "max"       => 1600,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui",

    );

    $of_options[] = array(
      "name"      => __("Medium Screen Width", "shoestrap"),
      "desc"      => __("The width of Normal screens. This is used to calculate the responsive layout breakpoints. Suitable for medium screens. Default: 992px", "shoestrap"),
      "id"        => "layout_screen_medium",
      "fold"      => "custom_grid",
      "std"       => 992,
      "min"       => 320,
      "step"      => 2,
      "max"       => 1600,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Large Screen Width", "shoestrap"),
      "desc"      => __("The width of Large screens. This is used to calculate the responsive layout breakpoints. Suitable for large screens. Default: 1200px", "shoestrap"),
      "id"        => "layout_screen_large",
      "fold"      => "custom_grid",
      "std"       => 1200,
      "min"       => 320,
      "step"      => 2,
      "max"       => 1600,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Columns Gutter", "shoestrap"),
      "desc"      => __("The space between the columns in your grid. Default: 30px", "shoestrap"),
      "id"        => "layout_gutter",
      "fold"      => "custom_grid",
      "std"       => 30,
      "min"       => 0,
      "step"      => 2,
      "max"       => 100,
      "advanced"  => true,
      "less"      => true,
      "type"      => "sliderui"
    );



    // NavBar Settings
    $of_options[] = array(
      "name"      => __("NavBar Settings", "shoestrap"),
      "type"      => "heading"
    );

    $url = admin_url( 'nav-menus.php' );
    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help7",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Navbar Options</h3>
                      <p>You can activate or deactivate your Primary NavBar here, and define its properties.
                      Please note that you might have to manually create a menu if it doesn't already exist
                      and add items to it from <a href=\"$url\">this page</a>.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Show the Main NavBar", "shoestrap"),
      "desc"      => __("ON by default. If you want to hide your main navbar you can do it here. When you do, the main menu will still be displayed but not styled as a navbar. If you want to completely disable it, then please visit the customizer and on the \"Navigation\" section, select \"None\".", "shoestrap"),
      "id"        => "navbar_toggle",
      "std"       => 1,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Menu Style", "shoestrap"),
      "desc"      => __("You can use an alternative menu style for your NavBars. OFF by default. ", "shoestrap"),
      "id"        => "navbar_style",
      "fold"      => "navbar_toggle",
      "std"       => 0,
      "type"      => "select",
      "customizer"=> array(),
      "options"   => array(
        0         => __( "Default", "shoestrap"),
        1         => __( "Style", "shoestrap") . " 1",
        2         => __( "Style", "shoestrap") . " 2",
        3         => __( "Style", "shoestrap") . " 3",
        4         => __( "Style", "shoestrap") . " 4",
        5         => __( "Style", "shoestrap") . " 5",
        6         => __( "Style", "shoestrap") . " 6",
      )
    );

    $of_options[] = array(
      "name"      => __("Display Branding (Sitename or Logo)", "shoestrap"),
      "desc"      => __("Default: ON", "shoestrap"),
      "id"        => "navbar_brand",
      "fold"      => "navbar_toggle",
      "std"       => 1,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Use Logo (if available) for branding", "shoestrap"),
      "desc"      => __("If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON", "shoestrap"),
      "id"        => "navbar_logo",
      "fold"      => "navbar_toggle",
      "std"       => 1,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("NavBar Background Color", "shoestrap"),
      "desc"      => __("Pick a background color for the NavBar. Default: #eeeeee.", "shoestrap"),
      "id"        => "navbar_bg",
      "fold"      => "navbar_toggle",
      "std"       => "#eeeeee",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("NavBar Text Color", "shoestrap"),
      "desc"      => __("Pick a color for the NavBar text. This applies to menu items and the Sitename (if no logo is uploaded). Default: #777777.", "shoestrap"),
      "id"        => "navbar_color",
      "fold"      => "navbar_toggle",
      "std"       => "#777777",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Display social links in the Navbar.", "shoestrap"),
      "desc"      => __("Display social links in the Navbar. These can be setup in the \"Social\" section on the left. Default: OFF", "shoestrap"),
      "id"        => "navbar_social",
      "fold"      => "navbar_toggle",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Search", "shoestrap"),
      "desc"      => __("Display a search form in the Navbar. Default: OFF", "shoestrap"),
      "id"        => "navbar_search",
      "fold"      => "navbar_toggle",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Float menu to the right", "shoestrap"),
      "desc"      => __("Floats the primary navigation to the right. Default: OFF", "shoestrap"),
      "id"        => "navbar_nav_right",
      "fold"      => "navbar_toggle",
      "std"       => 0,
      "customizer"=> array(),
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("NavBar Positioning", "shoestrap"),
      "desc"      => __("Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you're using one of the \"fixed\" options, the navbar will stay fixed on the top or bottom of the page. Default: Normal", "shoestrap"),
      "id"        => "navbar_position",
      "fold"      => "navbar_toggle",
      "std"       => 0,
      "type"      => "select",
      "customizer"=> array(),
      "options"   => array(
        0         => __( 'Normal', 'shoestrap' ),
        1         => __( 'Fixed to Top', 'shoestrap' ),
        2         => __( 'Fixed to Bottom', 'shoestrap' ),
      )
    );

    $of_options[] = array(
      "name"      => __("Navbar Height", "shoestrap"),
      "desc"      => __("Select the height of the Navbar. If you're using a logo then this should be equal or greater than its height.", "shoestrap"),
      "id"        => "navbar_height",
      "fold"      => "navbar_toggle",
      "std"       => 50,
      "min"       => 10,
      "step"      => 1,
      "max"       => 600,
      "less"      => true,
      "type"      => "sliderui"
    );

    // Jumbotron (Hero)
    $of_options[] = array(
      "name"      => __("Jumbotron", "shoestrap"),
      "type"      => "heading"
    );

    $url = admin_url( 'widgets.php' );
    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help8",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Jumbotron</h3>
                      <p>A \"Jumbotron\", also known as \"Hero\" area,
                      is an area in your site where you can display in a prominent position things that matter to you.
                      This can be a slideshow, some text or whatever else you wish.
                      This area is implemented as a widget area, so in order for something to be displayed
                      you will have to add a widget from <a href=\"$url\">here</a>.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Jumbotron Background Color", "shoestrap"),
      "desc"      => __("Select the background color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #EEEEEE.", "shoestrap"),
      "id"        => "jumbotron_bg",
      "std"       => "#EEEEEE",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Background Image", "shoestrap"),
      "desc"      => __("Upload a Background image using the media uploader, or define the URL directly. Use the shortcodes [site_url] or [site_url_secure] for setting default URLs", "shoestrap"),
      "id"        => "jumbotron_bg_img",
      "std"       => "",
      "customizer"=> array(),
      "type"      => "media"
    );

    $of_options[] = array(
      "name"      => __("Background Repeat", "shoestrap"),
      "desc"      => __("Select how (or if) the selected background should be tiled. Default: Tile", "shoestrap"),
      "id"        => "jumbotron_bg_repeat",
      "fold"      => "jumbotron_bg_img",
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
      "name"      => __("Background Alignment", "shoestrap"),
      "desc"      => __("Select how the selected background should be horizontally aligned. Default: Left", "shoestrap"),
      "id"        => "jumbotron_bg_pos_x",
      "fold"      => "jumbotron_bg_img",
      "std"       => "left",
      "type"      => "radio",
      "options"   => array(
        'left'    => __( 'Left', 'shoestrap' ),
        'right'   => __( 'Right', 'shoestrap' ),
        'center'  => __( 'Center', 'shoestrap' ),
      ),
    );

    $of_options[] = array(
      "name"      => __("Jumbotron Text Color", "shoestrap"),
      "desc"      => __("Select the text color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \"Jumbotron\" Widget Area. Default: #333333.", "shoestrap"),
      "id"        => "jumbotron_color",
      "std"       => "#333333",
      "less"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Display Jumbotron only on the Frontpage.", "shoestrap"),
      "desc"      => __("When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON", "shoestrap"),
      "id"        => "jumbotron_visibility",
      "customizer"=> array(),
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Full-Width", "shoestrap"),
      "desc"      => __("When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.", "shoestrap"),
      "id"        => "jumbotron_nocontainer",
      "customizer"=> array(),
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Use fittext script for the title.", "shoestrap"),
      "desc"      => __("Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF", "shoestrap"),
      "id"        => "jumbotron_title_fit",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Center-align the content.", "shoestrap"),
      "desc"      => __("Turn this on to center-align the contents of the Jumbotron area. Default: OFF", "shoestrap"),
      "id"        => "jumbotron_center",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => "Jumbotron Bottom Border",
      "desc"      => __("Select the border options for your Jumbotron", "shoestrap"),
      "id"        => "jumbotron_border_bottom",
      "type"      => "border",
      "std"       => array(
        'width'   => '0',
        'style'   => 'solid',
        'color'   => "#428bca",
      )
    );

    // Header
    $of_options[] = array(
      "name"      => __("Header", "shoestrap"),
      "type"      => "heading"
    );

    $url = admin_url( 'widgets.php' );
    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help9",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Jumbotron</h3>
                      <p>You can enable an extra header from here. In this header you can add your logo, and any other widgets you wish.
                      To add widgets on your header, visit <a href=\"$url\">this page</a>.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Display the Header.", "shoestrap"),
      "desc"      => __("Turn this ON to display the header. Default: OFF", "shoestrap"),
      "id"        => "header_toggle",
      "customizer"=> array(),
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Display branding on your Header.", "shoestrap"),
      "desc"      => __("Turn this ON to display branding (Sitename or Logo)on your Header. Default: ON", "shoestrap"),
      "id"        => "header_branding",
      "customizer"=> array(),
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Header Background Color", "shoestrap"),
      "desc"      => __("Select the background color for your header. Default: #EEEEEE.", "shoestrap"),
      "id"        => "header_bg",
      "std"       => "#EEEEEE",
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Header Text Color", "shoestrap"),
      "desc"      => __("Select the text color for your header. Default: #333333.", "shoestrap"),
      "id"        => "header_color",
      "std"       => "#333333",
      "customizer"=> array(),
      "type"      => "color"
    );

    // Footer
    $of_options[] = array(
      "name"      => __("Footer", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Footer Background Color", "shoestrap"),
      "desc"      => __("Select the background color for your footer. Default: #ffffff.", "shoestrap"),
      "id"        => "footer_background",
      "std"       => "#ffffff",
      "customizer"=> array(),
      "type"      => "color"
    );
    $of_options[] = array(
      "name"      => __("Footer Background Opacity", "shoestrap"),
      "desc"      => __("Select the opacity level for the footer bar. Default: 100%.", "shoestrap"),
      "id"        => "footer_opacity",
      "std"       => 100,
      "min"       => 30,
      "max"       => 100,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Footer Text Color", "shoestrap"),
      "desc"      => __("Select the text color for your footer. Default: #333333.", "shoestrap"),
      "id"        => "footer_color",
      "std"       => "#333333",
      "customizer"=> array(),
      "type"      => "color"
    );

    $of_options[] = array(
      "name"      => __("Footer Text", "shoestrap"),
      "desc"      => __("The text that will be displayed in your footer. Default: your site's name.", "shoestrap"),
      "id"        => "footer_text",
      "std"       => get_bloginfo( 'name' ),
      "customizer"=> array(),
      "type"      => "textarea"
    );

    $of_options[] = array(
      "name"      => "Footer Top Border",
      "desc"      => "Select the border options for your Footer",
      "id"        => "footer_border_top",
      "type"      => "border",
      "std"       => array(
        'width'   => '2',
        'style'   => 'solid',
        'color'   => shoestrap_getVariable( 'color_brand_info' ),
      )
    );

    do_action('shoestrap_pro_footer');

    // Typography
    $of_options[] = array(
      "name"      => __("Typography", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Font Size Base", "shoestrap"),
      "desc"      => __("The basic font size. Based on this, all the other text elements will also be calculated (for example titles etc).", "shoestrap"),
      "id"        => "typography_font_size_base",
      "std"       => 14,
      "min"       => 9,
      "step"      => 1,
      "max"       => 22,
      "less"      => true,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Font", "shoestrap"),
      "desc"      => __("The main font for your site.", "shoestrap"),
      "id"        => "typography_sans_serif",
      "std"       => "'Helvetica Neue', Helvetica, Arial, sans-serif",
      "type"      => "text",
    );


    // Blog Options
    $of_options[] = array(
      "name"      => __("Blog Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Show BreadCrumbs", "shoestrap"),
      "desc"      => __("Display Breadcrumbs. Default: OFF.", "shoestrap"),
      "id"        => "breadcrumbs",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help3",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Featured Images</h3>
                      <p>Here you can select if you want to display the featured images in post archives and individual posts.
                      Please note that these apply to posts, pages, as well as custom post types.
                      You can select image sizes independently for archives and individual posts view.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $of_options[] = array(
      "name"      => __("Featured Images on Archives", "shoestrap"),
      "desc"      => __("Display featured Images on post archives (such as categories, tags, month view etc). Default: OFF.", "shoestrap"),
      "id"        => "feat_img_archive",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Archives Featured Image Width", "shoestrap"),
      "desc"      => __("Select the width of your featured images on post archives. Default: 550px", "shoestrap"),
      "id"        => "feat_img_archive_width",
      "fold"      => "feat_img_archive",
      "std"       => 550,
      "min"       => 100,
      "step"      => 1,
      "max"       => 1600,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Archives Featured Image Height", "shoestrap"),
      "desc"      => __("Select the height of your featured images on post archives. Default: 300px", "shoestrap"),
      "id"        => "feat_img_archive_height",
      "fold"      => "feat_img_archive",
      "std"       => 300,
      "min"       => 50,
      "step"      => 1,
      "max"       => 1000,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Featured Images on Posts", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_post",
      "std"       => 1,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Posts Featured Image Width", "shoestrap"),
      "desc"      => __("Select the width of your featured images on single posts. Default: 550px", "shoestrap"),
      "id"        => "feat_img_post_width",
      "fold"      => "feat_img_post",
      "std"       => 550,
      "min"       => 100,
      "step"      => 1,
      "max"       => 1600,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Posts Featured Image Height", "shoestrap"),
      "desc"      => __("Select the height of your featured images on single posts. Default: 300px", "shoestrap"),
      "id"        => "feat_img_post_height",
      "fold"      => "feat_img_post",
      "std"       => 330,
      "min"       => 50,
      "step"      => 1,
      "max"       => 1000,
      "type"      => "sliderui"
    );

    // Social
    $of_options[] = array(
      "name"      => __("Social Sharing Box", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Facebook", "shoestrap"),
      "desc"      => __("Show the Facebook sharing icon in blog posts.", "shoestrap"),
      "id"        => "facebook_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Google+", "shoestrap"),
      "desc"      => __("Show the Google+ sharing icon in blog posts.", "shoestrap"),
      "id"        => "google_plus_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("LinkedIn", "shoestrap"),
      "desc"      => __("Show the LinkedIn sharing icon in blog posts.", "shoestrap"),
      "id"        => "linkedin_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Pinterest", "shoestrap"),
      "desc"      => __("Show the Pinterest sharing icon in blog posts.", "shoestrap"),
      "id"        => "pinterest_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Reddit", "shoestrap"),
      "desc"      => __("Show the Reddit sharing icon in blog posts.", "shoestrap"),
      "id"        => "reddit_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Tumblr", "shoestrap"),
      "desc"      => __("Show the Tumblr sharing icon in blog posts.", "shoestrap"),
      "id"        => "tumblr_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Twitter", "shoestrap"),
      "desc"      => __("Show the Twitter sharing icon in blog posts.", "shoestrap"),
      "id"        => "twitter_share",
      "std"       => "",
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Email", "shoestrap"),
      "desc"      => __("Show the Email sharing icon in blog posts.", "shoestrap"),
      "id"        => "email_share",
      "std"       => "",
      "type"      => "switch"
    );

    // Social
    $of_options[] = array(
      "name"      => __("Social Sharing", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Blogger", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "blogger_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("DeviantART", "shoestrap"),
      "desc"      => __("Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "deviantart_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Digg", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "digg_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Dribbble", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "dribbble_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Facebook", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "facebook_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Flickr", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "flickr_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Forrst", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Forrst icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "forrst_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("GitHub", "shoestrap"),
      "desc"      => __("Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "github_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Google+", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "google_plus_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("LinkedIn", "shoestrap"),
      "desc"      => __("Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "linkedin_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("MySpace", "shoestrap"),
      "desc"      => __("Provide the link you desire and the MySpace icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "myspace_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Pinterest", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "pinterest_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Reddit", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "reddit_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("RSS", "shoestrap"),
      "desc"      => __("Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "rss_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Skype", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "skype_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("SoundCloud", "shoestrap"),
      "desc"      => __("Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "soundcloud_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Tumblr", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "tumblr_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Twitter", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "twitter_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => __("Vimeo", "shoestrap"),
      "desc"      => __("Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.", "shoestrap"),
      "id"        => "vimeo_link",
      "std"       => "",
      "type"      => "text"
    );


    $of_options[] = array(
      "name"      => "Vkontakte",
      "desc"      => "Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.",
      "id"        => "vkontakte_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => "Yahoo TODO",
      "desc"      => "Provide the link you desire and the Yahoo icon will appear. To remove it, just leave it blank.",
      "id"        => "yahoo_link",
      "std"       => "",
      "type"      => "text"
    );

    $of_options[] = array(
      "name"      => "YouTube Link",
      "desc"      => "Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.",
      "id"        => "youtube_link",
      "std"       => "",
      "type"      => "text"
    );




    // Advanced Settings
    $of_options[] = array(
      "name"      => __("Advanced Settings", "shoestrap"),
      "type"      => "heading"
    );

    $url = admin_url( 'widgets.php' );
    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help10",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">CAUTION</h3>
                      <p>The settings bellow can pottentially harm your site if you do not properly comprehend them and what they do.
                      If unsure, simply let them be.</p>",
      "icon"      => true,
      "type"      => "info"
    );

    $url = admin_url( 'options-permalink.php' );
    $of_options[] = array(
      "name"      => __("URL Rewrites", "shoestrap"),
      "desc"      => __("Rewrites URLs, masking partially the fact that you're using WordPress. Please note that after you enable or disable this option, you should visit the <a href='$url'>permalinks menu</a> and press <strong>save</strong>. This option requires that your .htaccess file is writable by your webserver. Default: OFF", "shoestrap"),
      "id"        => "rewrites",
      "std"       => 0,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Root Relative URLs", "shoestrap"),
      "desc"      => __("Return URLs such as <em>/assets/css/style.css</em> instead of <em>http://example.com/assets/css/style.css</em>. Default: ON", "shoestrap"),
      "id"        => "root_relative_urls",
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Enable Nice Search", "shoestrap"),
      "desc"      => __("Redirects /?s=query to /search/query/, convert %20 to +. Default: ON", "shoestrap"),
      "id"        => "nice_search",
      "std"       => 1,
      "type"      => "switch"
    );

    $of_options[] = array(
      "name"      => __("Custom CSS", "shoestrap"),
      "desc"      => __("You can write your custom CSS here.", "shoestrap"),
      "id"        => "user_css",
      "std"       => "",
      "type"      => "textarea"
    );

    $of_options[] = array(
      "name"      => __("Custom JS", "shoestrap"),
      "desc"      => __("You can write your custom JavaScript/jQuery here.", "shoestrap"),
      "id"        => "user_js",
      "std"       => "",
      "type"      => "textarea"
    );

    // Presets Styles
    $of_options[] = array(
      "name"      => __("Preset Styles", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Choose a Preset", "shoestrap"),
      "desc"      => __("Select a site preset. You can load it in and replace your current styles.", "shoestrap"),
      "id"        => "design_preset",
      "std"       => "",
      "type"      => "presets",
      "options"   => $preset_styles,
    );

    // Backup Options
    $of_options[] = array(
      "name"      => __("Backup Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Backup and Restore Options", "shoestrap"),
      "id"        => "of_backup",
      "std"       => "",
      "type"      => "backup",
      "desc"      => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', "shoestrap"),
    );

    $of_options[] = array(
      "name"      => __("Transfer Theme Options Data", "shoestrap"),
      "id"        => "of_transfer",
      "std"       => "",
      "type"      => "transfer",
      "desc"      => __('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', "shoestrap"),
    );

    do_action('smof_options_modifier');

    $smof_details = array();
    foreach($of_options as $option) {
      $smof_details[$option['id']] = $option;
    }


  }
}

remove_filter('of_options_before_save', 'of_filter_save_media_upload');
