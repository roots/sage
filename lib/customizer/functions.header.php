<?php

/*
 * The Header template
 */
function shoestrap_branding() {
  if ( get_theme_mod( 'header_toggle' ) == 1 ) { ?>
    <div class="header-wrapper">
      <div class="container">
        <?php if ( get_theme_mod( 'header_branding' ) == 1 ) { ?>
          <a class="brand-logo pull-left" href="<?php echo home_url(); ?>/">
            <h1><?php if ( function_exists( 'shoestrap_logo' ) ) { shoestrap_logo(); } ?></h1>
          </a>
        <?php } ?>
        <?php if ( get_theme_mod( 'header_branding' ) == 1 ) { ?>
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
  $bg = get_theme_mod( 'header_bg', '#EEEEEE' );
  $cl = get_theme_mod( 'header_color', '#333333' );

  if ( get_theme_mod( 'header_toggle' ) == 1 ) {
    echo '<style>.header-wrapper{background:' . $bg . '; color:' . $cl . ';}</style>';
  }
}
add_action( 'wp_head', 'shoestrap_header_css' );