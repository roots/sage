<?php

/*
 * The branding core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_branding_options' ) ) {
  function shoestrap_module_branding_options($sections) {
  	$fields = array();
    // Branding Options
    $section = array(
    		'title' => __("Branding", "shoestrap"),
    		'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_119_adjust.png',
    	);

    $fields[] = array(
      "name"      => __("Logo", "shoestrap"),
      "desc"      => __("Upload a logo image using the media uploader, or define the URL directly.", "shoestrap"),
      "id"        => "logo",
      "std"       => "",
      "type"      => "media",
      "customizer"=> array(),
    );

    $fields[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "retina_help",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Retina Logo</h3>
                      <p>Upload a logo that is exactly 2x the size you want to typically display. A version will then be generated for general site use. If you have previously uploaded a logo, you will need to re-upload it to generate the proper versions.</p>",
      "icon"      => true,
      "fold"      => "retina_logo_toggle",
      "type"      => "info"
    );

    $fields[] = array(
      "name"      => __("Custom Favicon", "shoestrap"),
      "desc"      => __("Upload a .ico image using the media uploader, or define the URL directly. 32px x 32px", "shoestrap"),
      "id"        => "favicon",
      "std"       => "",
      "type"      => "media",
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Apple Icon", "shoestrap"),
      "desc"      => __("This will create icons for Apple iPhone (57px x 57px), Apple iPhone Retina Version (114px x 114px), Apple iPad (72px x 72px) and Apple iPad Retina (144px x 144px). Please note that for better results the image you upload should be at least 144px x 144px.", "shoestrap"),
      "id"        => "apple_icon",
      "std"       => "",
      "type"      => "media",
      "fold"      => "advanced_toggle"
    );


    $fields[] = array(
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

    $fields[] = array(
      "name"      => __("Brand Colors: Primary", "shoestrap"),
      "desc"      => __("Select your primary branding color. This will affect various areas of your site, including the color of your primary buttons, the background of some elements and many more.", "shoestrap"),
      "id"        => "color_brand_primary",
      "std"       => "#428bca",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );

    $fields[] = array(
      "name"      => __("Brand Colors: Secondary", "shoestrap"),
      "desc"      => __("Select your secondary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.", "shoestrap"),
      "id"        => "color_brand_secondary",
      "std"       => "#428bca",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color"
    );      

    $fields[] = array(
      "name"      => __("Brand Colors: Success", "shoestrap"),
      "desc"      => __("Select your branding color for success messages etc. Default: #5cb85c.", "shoestrap"),
      "id"        => "color_brand_success",
      "std"       => "#5cb85c",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color",
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Brand Colors: Warning", "shoestrap"),
      "desc"      => __("Select your branding color for warning messages etc. Default: #f0ad4e.", "shoestrap"),
      "id"        => "color_brand_warning",
      "std"       => "#f0ad4e",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color",
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Brand Colors: Danger", "shoestrap"),
      "desc"      => __("Select your branding color for success messages etc. Default: #d9534f.", "shoestrap"),
      "id"        => "color_brand_danger",
      "std"       => "#d9534f",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color",
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Brand Colors: Info", "shoestrap"),
      "desc"      => __("Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \"info\" class. Default: #5bc0de.", "shoestrap"),
      "id"        => "color_brand_info",
      "std"       => "#5bc0de",
      "compiler"      => true,
      "customizer"=> array(),
      "type"      => "color",
      "fold"      => "advanced_toggle"
    );

    $section['fields'] = $fields;

    do_action( 'shoestrap_module_branding_options_modifier' );
    
    array_push($sections, $section);
    return $sections;

  }
}
add_action( 'shoestrap_add_sections', 'shoestrap_module_branding_options', 50 );



include_once( dirname(__FILE__).'/functions.logo.php' );
include_once( dirname(__FILE__).'/functions.icons.php' );
