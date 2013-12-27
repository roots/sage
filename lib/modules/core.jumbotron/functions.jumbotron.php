<?php

if ( !function_exists( 'jumbotron_content' ) ) :
/*
 * The content of the hero region
 * according to what we've entered in the customizer
 */
function jumbotron_content() {
  $hero         = false;
  $site_style   = shoestrap_getVariable( 'site_style' );
  $visibility   = shoestrap_getVariable( 'jumbotron_visibility' );
  $nocontainer  = shoestrap_getVariable( 'jumbotron_nocontainer' );

  if ( ( ( $visibility == 1 && is_front_page() ) || $visibility != 1 ) && is_active_sidebar( 'Jumbotron' ) )
    $hero = true;
  ?>

  <div class="clearfix"></div>

  <?php if ( $hero == true ) : ?>
    <?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
      <div class="' . shoestrap_container_class() . '">
    <?php endif; ?>

    <div class="jumbotron">

      <?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
        <div class="' . shoestrap_container_class() . '">
      <?php endif; ?>

        <?php dynamic_sidebar('Jumbotron'); ?>

      <?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
        </div>
      <?php endif; ?>

    <?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
      </div>
    <?php endif; ?>

    </div>
  <?php endif;
}
endif;
add_action( 'shoestrap_below_top_navbar', 'jumbotron_content', 10 );


if ( !function_exists( 'shoestrap_jumbotron_css' ) ) :
function shoestrap_jumbotron_css() {
  $center = shoestrap_getVariable( 'jumbotron_center' );
  $border = shoestrap_getVariable( 'jumbotron_border_bottom' );

  $repeat   = '';
  $position = '';

  // $background is the saved custom image, or the default image.
  if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 ) {
    if ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != "" ) {
    	$jVar = shoestrap_getVariable( 'jumbotron_background_custom_image' );
      $background = set_url_scheme( $jVar['url'] );
    } elseif ( shoestrap_getVariable( 'jumbotron_background_image' ) != "" ) {
    	$jVar = shoestrap_getVariable( 'jumbotron_background_image' );
      $background = set_url_scheme( $jVar['url'] );
    }
    
  } elseif ( shoestrap_getVariable( 'jumbotron_background_pattern_toggle' ) == 1 && shoestrap_getVariable( 'jumbotron_background_pattern' ) != "" ) {
    $background = shoestrap_getVariable( 'jumbotron_background_pattern' );
  }

  $color = ( shoestrap_getVariable( 'jumbotron_background_color' ) != '' ) ? '#' . str_replace( '#', '', shoestrap_getVariable( 'jumbotron_background_color' ) ) : '';

  if ( !isset( $background ) && !isset( $color ) )
    return;

  $style = $color ? "background-color: $color;" : '';

  $style .= ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 1 ) ? 'background-attachment: fixed;' : '';

  $image = ( isset($background) && $background ) ? "background-image: url( '$background' );" : '';


  if ( shoestrap_getVariable( 'jumbotron_background_image_toggle' ) == 1 && ( shoestrap_getVariable( 'jumbotron_background_custom_image' ) != '' || shoestrap_getVariable( 'jumbotron_background_image' ) != '' ) ) {
    if ( shoestrap_getVariable( 'jumbotron_background_image_position_toggle' ) == 0 ) {
      $style .= "background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-position: 50% 50%;";

      $style .= ( shoestrap_getVariable( 'jumbotron_background_fixed_toggle' ) == 0 ) ? "background-repeat: no-repeat;" : '';
    
    } else {
      // Not fixed position, custom
      $repeat = shoestrap_getVariable( 'jumbotron_background_repeat' );

      $repeat = ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) ? 'repeat' : $repeat;

      $style .= ( $repeat == 'no-repeat' ) ? "background-size: auto;" : $repeat;

      $repeat = " background-repeat: $repeat;";

      $position = shoestrap_getVariable( 'jumbotron_background_position_x', 'left' );
      $position = ( !in_array( $position, array( 'center', 'right', 'left' ) ) ) ? 'left' : $position;
      $position = " background-position: top $position;";
    }
  }

  $style .= $image . $repeat . $position;

  $style .= ( $center == 1 ) ? 'text-align: center;' : '';

  $style .= ( !empty($border) && $border['border-bottom'] > 0 && !empty($border['border-color']) ) ? 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';' : '';

  $style .= 'margin-bottom: 0px;';

  $theCSS = '.jumbotron {' . trim( $style ) . '}';
  $theCSS .= $color ? ".jumbotron{background: $color;}" : '';
  
  wp_add_inline_style( 'shoestrap_css', $theCSS );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_jumbotron_css', 101 );


if ( !function_exists( 'jumbotron_fittext' ) ) :
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
endif;
add_action( 'wp_footer', 'jumbotron_fittext', 10 );


if ( !function_exists( 'jumbotron_fittext_enqueue_script' ) ) :
/*
 * Enqueues fittext.js when needed
 */
function jumbotron_fittext_enqueue_script() {
  $fittext_toggle   = shoestrap_getVariable( 'jumbotron_title_fit' );
  $jumbo_visibility = shoestrap_getVariable( 'jumbotron_visibility' );

  if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
    wp_register_script('fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false);
    wp_enqueue_script('fittext');
  }
}
endif;
add_action('wp_enqueue_scripts', 'jumbotron_fittext_enqueue_script', 101);

if ( !function_exists( 'shoestrap_conditional_jumbo_section_removal' ) ) :
/*
 * Removes the Jumbotron section from the customizer
 * if there are no widgets in the Jumbotron widget area.
 */
function shoestrap_conditional_jumbo_section_removal( $wp_customize ) {
  if ( !is_active_sidebar( 'jumbotron' ) )
    $wp_customize->remove_section( 'jumbotron');
}
endif;
add_action( 'customize_register', 'shoestrap_conditional_jumbo_section_removal' );