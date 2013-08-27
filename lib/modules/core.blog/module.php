<?php

/*
 * The blog core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_blog_options' ) ) {
  function shoestrap_module_blog_options($sections) {

    // Blog Options
    $section = array(
    		'title' => __("Blog", "shoestrap"),
    		'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_235_pen.png',
    	);    

    $fields[] = array(
      "name"      => __("Widgets mode", "shoestrap"),
      "desc"      => __("How do you want your widgets to be displayed?", "shoestrap"),
      "id"        => "widgets_mode",
      "std"       => 1,
      "fold"       => 'advanced_toggle',
      "off"       => __('Panel', "shoestrap"),
      "on"        => __('Well', "shoestrap"),
      "type"      => "switch",
      "customizer"=> array(),
    );

    $fields[] = array(
      "name"      => __("Custom Blog Layout", "shoestrap"),
      "desc"      => __("Set a default layout for your blog/post pages. Default: OFF.", "shoestrap"),
      "id"        => "blog_layout_toggle",
      "compiler"      => true,
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Blog Layout", "shoestrap"),
      "desc"      => __("Override your default styling. Choose between 1, 2 or 3 column layout.", "shoestrap"),
      "id"        => "blog_layout",
      "std"       => get_theme_mod('layout', 1),
      "type"      => "images",
      "compiler"      => true,
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

    $fields[] = array(
      "name"      => __("Enable Comments on Blog", "shoestrap"),
      "desc"      => __("Allow site visitors to write comments on blog posts. Default: On.", "shoestrap"),
      "id"        => "blog_comments_toggle",
      "std"       => 1,
      "type"      => "switch",
      "customizer"=> array(),
    );

    $fields[] = array(
      "name"      => __("Post excerpt length", "shoestrap"),
      "desc"      => __("Select the height of your featured images on post archives. Default: 40px", "shoestrap"),
      "id"        => "post_excerpt_length",
      "std"       => 40,
      "min"       => 10,
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "slider"
    );

    $fields[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "help3",
      "std"       => "<h3 style=\"margin: 0 0 10px;\">Featured Images</h3>
                      <p>Here you can select if you want to display the featured images in post archives and individual posts.
                      Please note that these apply to posts, pages, as well as custom post types.
                      You can select image sizes independently for archives and individual posts view.</p>",
      "icon"      => true,
      "type"      => "info",
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Featured Images on Archives", "shoestrap"),
      "desc"      => __("Display featured Images on post archives (such as categories, tags, month view etc). Default: OFF.", "shoestrap"),
      "id"        => "feat_img_archive",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
      "fold"      => "advanced_toggle"
    );


    $fields[] = array(
      "name"      => __("Featured Images on Archives Full Width", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_archive_custom_toggle",
      "std"       => 0,
      "fold"       => 'feat_img_archive',
      "off"       => __('Full Width', "shoestrap"),
      "on"        => __('Custom Dimensions', "shoestrap"),
      "type"      => "switch",
      "customizer"=> array(),
    );

    $fields[] = array(
      "name"      => __("Archives Featured Image Width", "shoestrap"),
      "desc"      => __("Select the width of your featured images on single posts. Default: 550px", "shoestrap"),
      "id"        => "feat_img_archive_width",
      "std"       => 550,
      "min"       => 100,
      "fold"      => 'feat_img_archive_custom_toggle',
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "slider"
    );

    $fields[] = array(
      "name"      => __("Archives Featured Image Height", "shoestrap"),
      "desc"      => __("Select the height of your featured images on post archives. Default: 300px", "shoestrap"),
      "id"        => "feat_img_archive_height",
      "fold"      => 'feat_img_archive_custom_toggle',
      "std"       => 300,
      "min"       => 50,
      "step"      => 1,
      "edit"      => 1,
      "max"       => 1000,
      "type"      => "slider"
    );

    $fields[] = array(
      "name"      => __("Featured Images on Posts", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_post",
      "std"       => 0,
      "type"      => "switch",
      "customizer"=> array(),
      "fold"      => "advanced_toggle"
    );

    $fields[] = array(
      "name"      => __("Featured Images on Posts Full Width", "shoestrap"),
      "desc"      => __("Display featured Images on posts. Default: OFF.", "shoestrap"),
      "id"        => "feat_img_post_custom_toggle",
      "std"       => 0,
      "fold"      => 'feat_img_post',
      "off"       => __('Full Width', "shoestrap"),
      "on"        => __('Custom Dimensions', "shoestrap"),
      "type"      => "switch",
      "customizer"=> array(),
    );

    $fields[] = array(
      "name"      => __("Posts Featured Image Width", "shoestrap"),
      "desc"      => __("Select the width of your featured images on single posts. Default: 550px", "shoestrap"),
      "id"        => "feat_img_post_width",
      "std"       => 550,
      "min"       => 100,
      "fold"      => 'feat_img_post_custom_toggle',
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "slider"
    );

    $fields[] = array(
      "name"      => __("Posts Featured Image Height", "shoestrap"),
      "desc"      => __("Select the height of your featured images on single posts. Default: 330px", "shoestrap"),
      "id"        => "feat_img_post_height",
      "fold"      => 'feat_img_post_custom_toggle',
      "std"       => 330,
      "min"       => 50,
      "step"      => 1,
      "max"       => 1000,
      "edit"      => 1,
      "type"      => "slider"
    );   

    $section['fields'] = $fields;

    do_action('shoestrap_module_blog_options_modifier');
    
    array_push($sections, $section);
    return $sections;

  }
}
add_action( 'shoestrap_add_sections', 'shoestrap_module_blog_options', 75 );


function shoestrap_core_blog_comments_toggle() {
  if ( shoestrap_getVariable('blog_comments_toggle' ) != 1 ) {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'trackbacks' );

    add_filter('get_comments_number', '__return_false', 10, 3);
  }
}

add_action( 'init','shoestrap_core_blog_comments_toggle', 1 );
