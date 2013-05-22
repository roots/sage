<?php

function shoestrap_background_css() {
  global $smof_data;
  // $background is the saved custom image, or the default image.
  if ( get_theme_mod( 'background_image_toggle' ) == 1 )
    $background = set_url_scheme( get_theme_mod( 'background_image') );
  else
    $background = get_theme_mod('bg_pattern');
  
  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = $smof_data['jumbotron_bg'];

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = $smof_data['background_repeat'];
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = get_theme_mod( 'background_position_x', 'left' );
    if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
      $position = 'left';
    $position = " background-position: top $position;";

    $style .= $image . $repeat . $position;
  }
  echo '<style>body{' . trim( $style ) . ';}</style>';
}
if ( get_theme_mod('background_image_toggle') == 1 || get_theme_mod('bg_pattern_toggle') == 1 )
  add_action( 'wp_head', 'shoestrap_background_css' );