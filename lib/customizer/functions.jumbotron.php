<?php

/*
 * The content of the hero region
 * according to what we've entered in the customizer
 */
function jumbotron_content() {
  $hero = false;
    if ( ( shoestrap_getVariable( 'jumbotron_visibility' ) == 1 && is_front_page() ) || shoestrap_getVariable( 'jumbotron_visibility' ) != 1 ) {
      if ( is_active_sidebar( 'Jumbotron' ) ) {
        $hero = true;
      }
    }

  if ( $hero == true ) :
    echo '<div class="jumbotron">';

    if ( shoestrap_getVariable( 'jumbotron_nocontainer' ) != 1 )
      echo '<div class="' . shoestrap_container_class() . '">';

    dynamic_sidebar('Jumbotron');

    if ( shoestrap_getVariable( 'jumbotron_nocontainer' ) != 1 )
      echo '</div>';

    echo '</div>';

  endif;
}
add_action( 'shoestrap_below_top_navbar', 'jumbotron_content', 10 );

function shoestrap_jumbotron_css() {
  $center = shoestrap_getVariable( 'jumbotron_center' );

  // $background is the saved custom image, or the default image.
  $background = set_url_scheme( shoestrap_getVariable( 'jumbotron_bg_img' ) );
  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = shoestrap_getVariable( 'jumbotron_bg' );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = shoestrap_getVariable( 'jumbotron_bg_repeat' );
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = shoestrap_getVariable( 'jumbotron_bg_pos_x' );
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

/*
 * Enables the fittext.js for h1 headings
 */
function jumbotron_fittext() {
  $fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
  $jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

  // Should only show on the front page if it's enabled, or site-wide when appropriate
  if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) )
    echo '<script>jQuery(".jumbotron h1").fitText(1.3);</script>';
}
add_action( 'wp_footer', 'jumbotron_fittext', 10 );

function jumbotron_fittext_enqueue_script() {
  $fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
  $jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

  if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
    wp_register_script('fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false);
    wp_enqueue_script('fittext');
  }
}
add_action('wp_enqueue_scripts', 'jumbotron_fittext_enqueue_script', 120);
