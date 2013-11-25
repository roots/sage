<?php

if ( shoestrap_getVariable( 'pjax' ) == 1 ) :
  if ( !function_exists( 'shoestrap_pjax_open_container' ) ) :
  function shoestrap_pjax_open_container() {
    echo '<div id="pjax-container">';
  }
  endif;
  add_action( 'shoestrap_pre_wrap', 'shoestrap_pjax_open_container' );

  if ( !function_exists( 'shoestrap_pjax_close_container' ) ) :
  function shoestrap_pjax_close_container() {
    echo '</div>';
  }
  endif;
  add_action( 'shoestrap_after_wrap', 'shoestrap_pjax_close_container' );

  if ( !function_exists( 'shoestrap_pjax_trigger_script' ) ) :
  function shoestrap_pjax_trigger_script() { ?>
    <script>
    $(document).on('pjax:send', function() {
      $('.main').fadeToggle("fast", "linear")
    })
    $(document).pjax('nav a, aside a, .breadcrumb a', '#pjax-container')
    </script>
    <?php
  }
  endif;
  add_action( 'wp_footer', 'shoestrap_pjax_trigger_script', 200 );
endif;