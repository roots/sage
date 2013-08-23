<?php
/**
 * Register sidebars and widgets
 */
function shoestrap_widgets_init() {
  // Sidebars
  register_sidebar(array(
    'name'          => __('Primary Sidebar', 'roots'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div><div class="panel-body">',
  ));

  register_sidebar(array(
    'name'          => __('Secondary Sidebar', 'shoestrap'),
    'id'            => 'sidebar-secondary',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div>',
  ));

  register_sidebar(array(
    'name'          => __('Jumbotron', 'shoestrap'),
    'id'            => 'jumbotron',
    'before_widget' => '<section id="%1$s"><div class="section-inner">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

  register_sidebar(array(
    'name'          => __('Header Area', 'shoestrap'),
    'id'            => 'header-area',
    'before_widget' => '<div class="container">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

  register_sidebar(array(
    'name'          => __('Footer Widget Area 1', 'shoestrap'),
    'id'            => 'sidebar-footer-1',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div>',
  ));

  register_sidebar(array(
    'name'          => __('Footer Widget Area 2', 'shoestrap'),
    'id'            => 'sidebar-footer-2',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div>',
  ));

  register_sidebar(array(
    'name'          => __('Footer Widget Area 3', 'shoestrap'),
    'id'            => 'sidebar-footer-3',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div>',
  ));

  register_sidebar(array(
    'name'          => __('Footer Widget Area 4', 'shoestrap'),
    'id'            => 'sidebar-footer-4',
    'before_widget' => '<section id="%1$s" class="panel panel-default widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="panel-heading">',
    'after_title'   => '</div>',
  ));

  // Widgets
  register_widget('Roots_Vcard_Widget');
}
add_action('widgets_init', 'shoestrap_widgets_init');
remove_action('widgets_init', 'roots_widgets_init');
