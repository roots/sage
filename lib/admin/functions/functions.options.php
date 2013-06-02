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

    $smof_details = array();
    foreach($of_options as $option) {
      $smof_details[$option['id']] = $option;
    }


  }
}

remove_filter('of_options_before_save', 'of_filter_save_media_upload');
