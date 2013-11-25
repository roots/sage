<?php

// Include the Breadcrumbs Class
include_once( dirname( __FILE__ ) . '/class.Shoestrap_Breadcrumb.php' );

if ( !function_exists( 'shoestrap_breadcrumbs' ) ) :
function shoestrap_breadcrumbs() {
  // No breadcrumbs on the front page
  if ( is_front_page() )
    return;

  $templates = array(
    'before'    => '<div class="breadTrail '.shoestrap_container_class().'"><ul class="breadcrumb">',
    'after'     => '</ul></div>',
    'standard'  => '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">%s</li>',
    'current'   => '<li class="active">%s</li>',
    'link'      => '<a href="%s" itemprop="url title">%s</a>'
  );
  $options = array(
    'show_htfpt' => true
  );

  if ( shoestrap_getVariable( 'breadcrumbs' ) != 0 ) :
    $breadcrumb = new Shoestrap_Breadcrumb( $templates, $options );
  endif;
}
endif;
add_action( 'shoestrap_breadcrumbs', 'shoestrap_breadcrumbs' );