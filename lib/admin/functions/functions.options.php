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
      "name"      => __("Social Sharing", "shoestrap"),
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
      "name"      => __("Social Links", "shoestrap"),
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
      "name"      => __("Advanced Options", "shoestrap"),
      "type"      => "heading"
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
      "std"       => "",
      "type"      => "text",
    );

    $of_options[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help2",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Border-Radius and Padding Base</h3>
                      <p>The following settings affect various areas of your site, most notably buttons.</p>",
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
