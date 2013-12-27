<?php

if ( !function_exists( 'shoestrap_nav_class_pull' ) ) :
function shoestrap_nav_class_pull( $class = 'navbar-nav' ) {
  $ul = ( shoestrap_getVariable( 'navbar_nav_right' ) == '1' ) ? 'nav pull-right ' . $class : 'nav ' . $class;

  return $ul;
}
endif;


if ( !function_exists( 'shoestrap_navbar_pre_searchbox' ) ) :
/*
 * The template for the primary navbar searchbox
 */
function shoestrap_navbar_pre_searchbox() {
  $show_searchbox = shoestrap_getVariable( 'navbar_search' );
  if ( $show_searchbox == '1' ) : ?>
    <form role="search" method="get" id="searchform" class="form-search pull-right navbar-form" action="<?php echo home_url('/'); ?>">
      <label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
      <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control search-query" placeholder="<?php _e('Search', 'roots'); ?> <?php bloginfo('name'); ?>">
    </form>
    <?php
  endif;
}
endif;
add_action( 'shoestrap_inside_nav_begin', 'shoestrap_navbar_pre_searchbox', 11 );


if ( !function_exists( 'shoestrap_navbar_class' ) ) :
function shoestrap_navbar_class( $navbar = 'main') {
  $fixed    = shoestrap_getVariable( 'navbar_fixed' );
  $fixedpos = shoestrap_getVariable( 'navbar_fixed_position' );
  $style    = shoestrap_getVariable( 'navbar_style' );

  if ( $fixed != 1 )
    $class = 'navbar navbar-static-top';
  else
    $class = ( $fixedpos == 1 ) ? 'navbar navbar-fixed-bottom' : 'navbar navbar-fixed-top';

  if ( $navbar != 'secondary' )
    return $class . ' ' . $style;
  else
    return 'navbar ' . $style;
}
endif;


if ( !function_exists( 'shoestrap_navbar_css' ) ) :
function shoestrap_navbar_css() {
  $navbar_bg_opacity = shoestrap_getVariable( 'navbar_bg_opacity' );
  $style = "";

  $opacity = ( $navbar_bg_opacity == '' ) ? '0' : ( intval( $navbar_bg_opacity ) ) / 100;

  if ( $opacity != 1 && $opacity != '' ) {
    $bg  = str_replace( '#', '', shoestrap_getVariable( 'navbar_bg' ) );
    $rgb = shoestrap_get_rgb( $bg, true );
    $opacityie = str_replace( '0.', '', $opacity );

    $style .= '.navbar, .navbar-default {';

    if ( $opacity != 1 && $opacity != '')
      $style .= 'background: transparent; background: rgba('.$rgb.', '.$opacity.'); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#'.$opacityie.$bg.',endColorstr=#'.$opacityie.$bg.'); ;';
    else
      $style .= 'background: #'.$bg.';';

    $style .= '}';

  }

  if ( shoestrap_getVariable( 'navbar_margin' ) != 1 )
    $style .= '.navbar-static-top { margin-top:'. shoestrap_getVariable( 'navbar_margin' ) .'px !important; margin-bottom:'. shoestrap_getVariable( 'navbar_margin' ) .'px !important; }';

  wp_add_inline_style( 'shoestrap_css', $style );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_navbar_css', 101 );