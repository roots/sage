<?php

function shoestrap_phpless(){
  
  $shoestrap_responsive = get_theme_mod( 'shoestrap_responsive' );
  
  if ( !class_exists( 'lessc' ) ) {
    require_once( TEMPLATEPATH . '/lib/less_compiler/lessc.inc.php' );
  }
  $less = new lessc;
  // $less->setFormatter( "compressed" );
  
  if ( $shoestrap_responsive == '0' ) {
    $less->checkedCompile( TEMPLATEPATH . '/assets/css/app-fixed.less', TEMPLATEPATH . '/assets/css/app-fixed.css' );
  } else {
    $less->checkedCompile( TEMPLATEPATH . '/assets/css/app-responsive.less', TEMPLATEPATH . '/assets/css/app-responsive.css' );
  }
}
add_action('wp', 'shoestrap_phpless');
