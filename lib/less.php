<?php

function bc_core_phpless(){
  if ( !class_exists( lessc ) ) {
    require_once( TEMPLATEPATH . '/lib/less_compiler/lessc.inc.php' );
  }
  $less = new lessc;
  $less->setFormatter( "compressed" );

  $less->checkedCompile( TEMPLATEPATH . '/assets/css/app.less', TEMPLATEPATH . '/assets/css/app.css' );
}
add_action('wp_head', 'bc_core_phpless');
