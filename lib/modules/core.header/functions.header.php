<?php

/*
 * The Header template
 */
function shoestrap_branding() {
  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) { ?>
    <div class="header-wrapper">
      <div class="container <?php echo shoestrap_container_class(); ?>">
        <?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) { ?>
          <a class="brand-logo pull-left" href="<?php echo home_url(); ?>/">
            <h1><?php if ( function_exists( 'shoestrap_logo' ) ) { shoestrap_logo(); } ?></h1>
          </a>
        <?php } ?>
        <?php if ( shoestrap_getVariable( 'header_branding' ) == 1 ) { ?>
          <div class="pull-right">
        <?php } else { ?>
          <div>
        <?php } ?>
        <?php dynamic_sidebar('header-area'); ?>
        </div>
      </div>
    </div>
  <?php
  }
}
add_action( 'shoestrap_below_top_navbar', 'shoestrap_branding', 5 );

function shoestrap_header_css() {
  $bg = shoestrap_getVariable( 'header_bg');
  $cl = shoestrap_getVariable( 'header_color' );
  $opacity = (intval(shoestrap_getVariable( 'header_bg_opacity' )))/100;

  $rgb = shoestrap_get_rgb($bg, true);

  if ( shoestrap_getVariable( 'header_toggle' ) == 1 ) {
    $style = '<style id="core.header">';
      $style .= '.header-wrapper{';
        if ($opacity != 1 && $opacity != "") {
          $style .= 'background: rgb('.$rgb.');';
          $style .= 'background: rgba('.$rgb.', '.$opacity.');';
        } else {
          $style .= 'background: '.$bg.';';
        }
        $style .= 'padding: 35px 15px;';
        $style .= 'max-width: 100%;';



      $style .= '}';
    $style .= '</style>';

    echo $style;
  }
}
add_action( 'wp_head', 'shoestrap_header_css' );
