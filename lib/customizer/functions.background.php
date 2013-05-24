<?php

function shoestrap_background_css() {
  $background_color = '#' . str_replace( '#', '', shoestrap_getVariable( 'color_body_bg' ) );
  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'background_image_toggle' ) == 1 )
    $background = set_url_scheme( shoestrap_getVariable( 'background_image') );
  else
    $background = shoestrap_getVariable('bg_pattern');

  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = shoestrap_getVariable( 'jumbotron_bg' );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    if (shoestrap_getVariable('background_image_position_toggle') == 0 && shoestrap_getVariable('bg_pattern_toggle') == 0) {
      $style .= "background-attachment: fixed;";
      $style .= "background-size: cover;";
      $style .= "background-position: 50% 50%;";
      $style .= "background-repeat: no-repeat no-repeat;";
    } else {
      $repeat = shoestrap_getVariable( 'background_repeat' );
      if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
        $repeat = 'repeat';
      $repeat = " background-repeat: $repeat;";

      $position = shoestrap_getVariable( 'background_position_x', 'left' );
      if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
        $position = 'left';
      $position = " background-position: top $position;";
    }


    $style .= $image . $repeat . $position;
  }
  echo '<style>body{' . trim( $style ) . ';}.wrap.main-section{background:' . $background_color . ';}</style>';
}
if ( shoestrap_getVariable('background_image_toggle') == 1 || shoestrap_getVariable('bg_pattern_toggle') == 1 )
  add_action( 'wp_head', 'shoestrap_background_css' );