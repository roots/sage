<?php

if ( !function_exists( 'shoestrap_remove_roots_widgets' ) ) :
/*
 * Remove default Roots widgets
 */
function shoestrap_remove_roots_widgets() {
  remove_action( 'widgets_init', 'roots_widgets_init' );
}
endif;
add_action( 'widgets_init', 'shoestrap_remove_roots_widgets', 1 );


if ( !function_exists( 'shoestrap_widgets_init' ) ) :
/**
 * Register sidebars and widgets
 */
function shoestrap_widgets_init() {
  $widgets_mode = shoestrap_getVariable( 'widgets_mode' );
  
  if ( $widgets_mode != 1 ) :
    $class        = 'panel panel-default';
    $before_title = '<div class="panel-heading">';
    $after_title  = '</div><div class="panel-body">';
  
  else :
    $class        = 'well';
    $before_title = '<h3 class="widget-title">';
    $after_title  = '</h3>';
  
  endif;

  // Sidebars
  register_sidebar( array(
    'name'          => __( 'Primary Sidebar', 'shoestrap' ),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  register_sidebar( array(
    'name'          => __( 'Secondary Sidebar', 'shoestrap' ),
    'id'            => 'sidebar-secondary',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  register_sidebar( array(
    'name'          => __( 'Jumbotron', 'shoestrap' ),
    'id'            => 'jumbotron',
    'before_widget' => '<section id="%1$s"><div class="section-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

  register_sidebar( array(
    'name'          => __( 'Header Area', 'shoestrap' ),
    'id'            => 'header-area',
    'before_widget' => '<div class="container">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

  register_sidebar( array(
    'name'          => __( 'Footer Widget Area 1', 'shoestrap' ),
    'id'            => 'sidebar-footer-1',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  register_sidebar( array(
    'name'          => __( 'Footer Widget Area 2', 'shoestrap' ),
    'id'            => 'sidebar-footer-2',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  register_sidebar( array(
    'name'          => __( 'Footer Widget Area 3', 'shoestrap' ),
    'id'            => 'sidebar-footer-3',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  register_sidebar( array(
    'name'          => __( 'Footer Widget Area 4', 'shoestrap' ),
    'id'            => 'sidebar-footer-4',
    'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => $before_title,
    'after_title'   => $after_title,
  ));

  // Widgets
  register_widget( 'Roots_Vcard_Widget' );
}
endif;
add_action( 'widgets_init', 'shoestrap_widgets_init' );