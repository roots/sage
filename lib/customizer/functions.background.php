<?php

function shoestrap_background_css() {
  $background_color = '#' . str_replace( '#', '', shoestrap_getVariable( 'color_body_bg' ) );
  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 ) {
    if ( shoestrap_getVariable( 'background_custom_image' ) != "" ) {
      $background = set_url_scheme( shoestrap_getVariable( 'background_custom_image') );  
    } else {
      $background = set_url_scheme( shoestrap_getVariable( 'background_image') );  
    }
    
  } else if ( shoestrap_getVariable('background_pattern') != "" ) {
    $background = shoestrap_getVariable('background_pattern');
  }
  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = shoestrap_getVariable( 'jumbotron_bg' );

  if ( ! $background && ! $color )
    return;


  $style = $color ? "background-color: #$color;" : '';

  if (shoestrap_getVariable('background_fixed_toggle') == 1) {
    $style .= "background-attachment: fixed;";
  }


  if ( $background ) {
    $image = " background-image: url('$background');";

    if (shoestrap_getVariable('background_image_position_toggle') == 0) {
      $style .= "background-size: cover;";
      $style .= "background-position: 50% 50%;";
      $style .= "background-repeat: no-repeat no-repeat;";

    } else if (shoestrap_getVariable('background_image_toggle') == 1) {
      $repeat = shoestrap_getVariable( 'background_repeat' );
      if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
        $repeat = 'repeat';
      $repeat = " background-repeat: $repeat $repeat;";

      $position = shoestrap_getVariable( 'background_position_x', 'left' );
      if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
        $position = 'left';
      $position = " background-position: top $position;";
    } else { // Pattern!
      echo "here@!";
      $style .= "background-position: top left;";

    }


    $style .= $image . $repeat . $position;
  }
  echo '<style>body{' . trim( $style ) . ';}.wrap.main-section{background:' . $background_color . ';}</style>';
}
if ( shoestrap_getVariable('background_image_toggle') == 1 || shoestrap_getVariable('background_pattern_toggle') == 1 )
  add_action( 'wp_head', 'shoestrap_background_css' );