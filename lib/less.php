<?php

function shoestrap_phpless(){
  
  $less = new lessc;
  // $less->setFormatter( "compressed" );
  $less->checkedCompile( STYLESHEETPATH . '/assets/css/style.less', STYLESHEETPATH . '/assets/css/style.css' );
}
add_action('wp', 'shoestrap_phpless');
