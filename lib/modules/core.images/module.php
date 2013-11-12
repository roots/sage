<?php

/*
 * The Featured Images core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_featured_images_options' ) ) :
function shoestrap_module_featured_images_options( $sections ) {

  // Blog Options
  $section = array( 
    'title'     => __( 'Featured Images', 'shoestrap' ),
    'icon'      => 'el-icon-picture icon-large',
  );

  $fields[] = array( 
    'id'        => 'help3',
    'title'     => __( 'Featured Images', 'shoestrap' ),
    'desc'      => __( 'Here you can select if you want to display the featured images in post archives and individual posts.
                    Please note that these apply to posts, pages, as well as custom post types.
                    You can select image sizes independently for archives and individual posts view.', 'shoestrap' ),
    'type'      => 'info',
  );

  $fields[] = array( 
    'title'     => __( 'Featured Images on Archives', 'shoestrap' ),
    'desc'      => __( 'Display featured Images on post archives ( such as categories, tags, month view etc ). Default: OFF.', 'shoestrap' ),
    'id'        => 'feat_img_archive',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> true,
  );


  $fields[] = array( 
    'title'     => __( 'Featured Images on Archives Full Width', 'shoestrap' ),
    'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
    'id'        => 'feat_img_archive_custom_toggle',
    'default'   => 0,
    'required'  => array('feat_img_archive','=',array('1')),
    'off'       => __( 'Full Width', 'shoestrap' ),
    'on'        => __( 'Custom Dimensions', 'shoestrap' ),
    'type'      => 'switch',
    'customizer'=> true,
  );

  $fields[] = array( 
    'title'     => __( 'Archives Featured Image Width', 'shoestrap' ),
    'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
    'id'        => 'feat_img_archive_width',
    'default'   => 550,
    'min'       => 100,
    'step'      => 1,
    'max'       => shoestrap_getVariable( 'screen_large_desktop' ),
    'required'  => array('feat_img_archive_custom_toggle','=',array('1')),
    'edit'      => 1,
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Archives Featured Image Height', 'shoestrap' ),
    'desc'      => __( 'Select the height of your featured images on post archives. Default: 300px', 'shoestrap' ),
    'id'        => 'feat_img_archive_height',
    'default'   => 300,
    'min'       => 50,
    'step'      => 1,
    'edit'      => 1,
    'max'       => shoestrap_getVariable( 'screen_large_desktop' ),
    'required'  => array('feat_img_archive_custom_toggle','=',array('1')),
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Featured Images on Posts', 'shoestrap' ),
    'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
    'id'        => 'feat_img_post',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> true,
  );

  $fields[] = array( 
    'title'     => __( 'Featured Images on Posts Full Width', 'shoestrap' ),
    'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'shoestrap' ),
    'id'        => 'feat_img_post_custom_toggle',
    'default'   => 0,
    'off'       => __( 'Full Width', 'shoestrap' ),
    'on'        => __( 'Custom Dimensions', 'shoestrap' ),
    'type'      => 'switch',
    'required'  => array('feat_img_post','=',array('1')),
    'customizer'=> true,
  );

  $fields[] = array( 
    'title'     => __( 'Posts Featured Image Width', 'shoestrap' ),
    'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'shoestrap' ),
    'id'        => 'feat_img_post_width',
    'default'   => 550,
    'min'       => 100,
    'step'      => 1,
    'max'       => shoestrap_getVariable( 'screen_large_desktop' ),
    'edit'      => 1,
    'required'  => array('feat_img_post_custom_toggle','=',array('1')),
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Posts Featured Image Height', 'shoestrap' ),
    'desc'      => __( 'Select the height of your featured images on single posts. Default: 330px', 'shoestrap' ),
    'id'        => 'feat_img_post_height',
    'default'   => 330,
    'min'       => 50,
    'step'      => 1,
    'max'       => shoestrap_getVariable( 'screen_large_desktop' ),
    'edit'      => 1,
    'required'  => array('feat_img_post_custom_toggle','=',array('1')),
    'type'      => 'slider'
  );

  $post_types = get_post_types( array( 'public' => true ), 'names' );
  $post_type_options  = array();
  $post_type_defaults = array();
  foreach ( $post_types as $post_type ) :
    $post_type_options[$post_type]  = $post_type;
    $post_type_defaults[$post_type] = 0;
  endforeach;

  $fields[] = array(
    'title'     => __( 'Disable featured images on single post types', 'shoestrap' ),
    'id'        => 'feat_img_per_post_type',
    'type'      => 'checkbox',
    'options'   => $post_type_options,
    'default'   => $post_type_defaults,
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_featured_images_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_featured_images_options', 90 );

if ( !function_exists( 'shoestrap_core_blog_comments_toggle' ) ) :
function shoestrap_core_blog_comments_toggle() {
  if ( shoestrap_getVariable( 'blog_comments_toggle' ) == 1 ) {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'trackbacks' );
    add_filter( 'get_comments_number', '__return_false', 10, 3 );
  }
}
endif;
add_action( 'init','shoestrap_core_blog_comments_toggle', 1 );

// Simply include our alternative functions for image resizing
include_once( dirname(__FILE__).'/resize.php' );
include_once( dirname(__FILE__).'/functions.images.php' );