<?php

/*
 * The content of the hero region
 * according to what we've entered in the customizer
 */
function jumbotron_content() {
  $hero = false;
    if ( ( get_theme_mod( 'jumbotron_visibility' ) == 1 && is_front_page() ) || get_theme_mod( 'jumbotron_visibility' ) != 1 ) {
      $hero = true;
    }

  if ( $hero == true ) :
    echo '<div class="jumbotron">';

    if ( get_theme_mod( 'jumbotron_nocontainer' ) != 1 )
      echo '<div class="container">';

    dynamic_sidebar('hero-area');

    if ( get_theme_mod( 'jumbotron_nocontainer' ) != 1 )
      echo '</div>';

    echo '</div>';

  endif;
}
add_action( 'shoestrap_below_top_navbar', 'jumbotron_content', 10 );

function shoestrap_jumbotron_css() {
  $center = get_theme_mod( 'jumbotron_center' );

  // $background is the saved custom image, or the default image.
  $background = set_url_scheme( get_theme_mod( 'jumbotron_bg_img' ) );
  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = get_theme_mod( 'jumbotron_bg' );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = get_theme_mod( 'jumbotron_bg_repeat', 'repeat' );
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = get_theme_mod( 'jumbotron_bg_pos_x', 'left' );
    if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
      $position = 'left';
    $position = " background-position: top $position;";

    $style .= $image . $repeat . $position;
  }
  echo '<style>';
  echo '.jumbotron{' . trim( $style ) . ';}';
  if ( $center == 1 )
    echo '.jumbotron{text-align: center;}';

  echo '</style>';
}
add_action( 'wp_head', 'shoestrap_jumbotron_css' );