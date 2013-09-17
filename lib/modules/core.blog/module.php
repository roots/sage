<?php

/*
 * The blog core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_blog_options' ) ) :
function shoestrap_module_blog_options( $sections ) {

  // Blog Options
  $section = array( 
    'title' => __( 'Blog', 'shoestrap' ),
    'icon'  => 'elusive icon-pencil icon-large',
  );

  $fields[] = array( 
    'title'     => __( 'Widgets mode', 'shoestrap' ),
    'desc'      => __( 'How do you want your widgets to be displayed?', 'shoestrap' ),
    'id'        => 'widgets_mode',
    'default'   => 1,
    // 'fold'      => 'advanced_toggle',
    'off'       => __( 'Panel', 'shoestrap' ),
    'on'        => __( 'Well', 'shoestrap' ),
    'type'      => 'switch',
    'customizer'=> array(),
  );

  $fields[] = array( 
    'title'     => __( 'Custom Blog Layout', 'shoestrap' ),
    'desc'      => __( 'Set a default layout for your blog/post pages. Default: OFF.', 'shoestrap' ),
    'id'        => 'blog_layout_toggle',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> array(),
    // 'fold'      => 'advanced_toggle'
  );

  $fields[] = array( 
    'title'     => __( 'Blog Layout', 'shoestrap' ),
    'desc'      => __( 'Override your default styling. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
    'id'        => 'blog_layout',
    'default'   => shoestrap_getVariable( 'layout', 1 ),
    'type'      => 'image_select',
    // 'fold'      => 'blog_layout_toggle',
    'customizer'=> array(),
    'options'   => array( 
      0         => REDUX_URL . 'assets/img/1c.png',
      1         => REDUX_URL . 'assets/img/2cr.png',
      2         => REDUX_URL . 'assets/img/2cl.png',
      3         => REDUX_URL . 'assets/img/3cl.png',
      4         => REDUX_URL . 'assets/img/3cr.png',
      5         => REDUX_URL . 'assets/img/3cm.png',
    )
  );

  $fields[] = array( 
    'title'     => __( 'Disable Comments on Blog', 'shoestrap' ),
    'desc'      => __( 'Do not allow site visitors to write comments on blog posts. Default: Off.', 'shoestrap' ),
    'id'        => 'blog_comments_toggle',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> array(),
  );

  $fields[] = array( 
    'title'     => __( 'Post excerpt length', 'shoestrap' ),
    'desc'      => __( 'Select the height of your featured images on post archives. Default: 40px', 'shoestrap' ),
    'id'        => 'post_excerpt_length',
    'default'   => 40,
    'min'       => 10,
    'step'      => 1,
    'max'       => 1000,
    'edit'      => 1,
    'type'      => 'slider'
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_blog_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_blog_options', 75 );

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