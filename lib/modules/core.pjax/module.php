<?php

if ( shoestrap_getVariable( 'pjax' ) == 1 ) {
  add_action( 'shoestrap_pre_wrap', 'shoestrap_pjax_open_container' );
  add_action( 'shoestrap_after_wrap', 'shoestrap_pjax_close_container' );
  add_action( 'wp_footer', 'shoestrap_pjax_trigger_script', 200 );
}


if ( !function_exists( 'shoestrap_pjax_open_container' ) ) :
function shoestrap_pjax_open_container() { ?>
  <div id="pjax-container">
  <?php
}
endif;
  

if ( !function_exists( 'shoestrap_pjax_close_container' ) ) :
function shoestrap_pjax_close_container() { ?>
  </div>
  <?php
}
endif;


if ( !function_exists( 'shoestrap_pjax_trigger_script' ) ) :
function shoestrap_pjax_trigger_script() { ?>
  <script>
  $(document).on('pjax:send', function() { $('.main').fadeToggle("fast", "linear") })
  $(document).pjax('nav a, aside a, .breadcrumb a', '#pjax-container')
  </script>
  <?php
}
endif;