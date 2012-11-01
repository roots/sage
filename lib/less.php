<?php

function shoestrap_phpless(){
  if ( !class_exists( 'lessc' ) ) {
    require_once( TEMPLATEPATH . '/lib/less_compiler/lessc.inc.php' );
  }
  $less = new lessc;
  $less->setFormatter( "compressed" );

  $less->checkedCompile( TEMPLATEPATH . '/assets/css/app.less', TEMPLATEPATH . '/assets/css/app.css' );
}
add_action('wp', 'shoestrap_phpless');
