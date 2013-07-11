<?php

/*
 * The blog core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_blog_options' ) ) {
  function shoestrap_module_blog_options() {

    /*-----------------------------------------------------------------------------------*/
    /* The Options Array */
    /*-----------------------------------------------------------------------------------*/

    // Set the Options Array
    global $of_options, $smof_details;

    // Blog Options
    $of_options[] = array(
      "name"      => __("Blog Options", "shoestrap"),
      "type"      => "heading"
    );

    $of_options[] = array(
      "name"      => __("Custom Blog Layout", "shoestrap"),
      "desc"      => __("Set a default layout for your blog/post pages. Default: OFF.", "shoestrap"),
      "id"        => "blog_layout_toggle",
      "less"      => true,
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Blog Layout", "shoestrap"),
      "desc"      => __("Override your default styling. Choose between 1, 2 or 3 column layout.", "shoestrap"),
      "id"        => "blog_layout",
      "std"       => get_theme_mod('layout', 1),
      "type"      => "images",
      "less"      => true,
      "fold"      => "blog_layout_toggle",
      "customizer"=> array(),
      "options"   => array(
        0         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/1c.png',
        1         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/2cr.png',
        2         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/2cl.png',
        3         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cl.png',
        4         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cr.png',
        5         => get_template_directory_uri() . SMOF_DIR . '/addons/assets/images/3cm.png',
      )
    );

    $of_options[] = array(
      "name"      => __("Enable Comments on Blog", "shoestrap"),
      "desc"      => __("Allow site visitors to write comments on blog posts. Default: On.", "shoestrap"),
      "id"        => "blog_comments_toggle",
      "std"       => 1,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Post excerpt length", "shoestrap"),
      "desc"      => __("Select the height of your featured images on post archives. Default: 40px", "shoestrap"),
      "id"        => "post_excerpt_length",
      "std"       => 40,
      "min"       => 10,
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "sliderui"
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
      "std"       => 1,
      "type"      => "switch",
      "customizer"=> array(),
    );


    $of_options[] = array(
      "name"      => __("Featured Images on Archives Full Width", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_archive_custom_toggle",
      "std"       => 0,
      "fold"      => 'feat_img_archive',
      "off"       => __('Full Width', "shoestrap"),
      "on"        => __('Custom Width', "shoestrap"),
      "type"      => "switch",
      "customizer"=> array(),
    );

    $of_options[] = array(
      "name"      => __("Archives Featured Image Width", "shoestrap"),
      "desc"      => __("Select the width of your featured images on single posts. Default: 550px", "shoestrap"),
      "id"        => "feat_img_archive_width",
      "fold"      => "feat_img_archive",
      "std"       => 550,
      "min"       => 100,
      "fold"      => 'feat_img_post_custom_toggle',
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
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
      "edit"      => 1,
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
      "name"      => __("Featured Images on Posts Full Width", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_post_custom_toggle",
      "std"       => 0,
      "fold"      => 'feat_img_post',
      "off"       => __('Full Width', "shoestrap"),
      "on"        => __('Custom Width', "shoestrap"),
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
      "fold"      => 'feat_img_post_custom_toggle',
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "sliderui"
    );

    $of_options[] = array(
      "name"      => __("Posts Featured Image Height", "shoestrap"),
      "desc"      => __("Select the height of your featured images on single posts. Default: 330px", "shoestrap"),
      "id"        => "feat_img_post_height",
      "fold"      => "feat_img_post",
      "std"       => 330,
      "min"       => 50,
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "sliderui"
    );   

    do_action('shoestrap_module_blog_options_modifier');

    $smof_details = array();
    foreach( $of_options as $option ) {
      if (isset($option['id']))
        $smof_details[$option['id']] = $option;
    }
  }
}
add_action( 'init','shoestrap_module_blog_options', 75 );

function shoestrap_core_blog_comments_toggle() {
  if ( shoestrap_getVariable('blog_comments_toggle' ) != 1 ) {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'trackbacks' );

    add_filter('get_comments_number', '__return_false', 10, 3);
  }
}

add_action( 'init','shoestrap_core_blog_comments_toggle', 1 );
