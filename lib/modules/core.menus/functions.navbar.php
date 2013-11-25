<?php

if ( !function_exists( 'shoestrap_nav_class_pull' ) ) :
function shoestrap_nav_class_pull( $class = 'navbar-nav' ) {
  if ( shoestrap_getVariable( 'navbar_nav_right' ) == '1' ) :
    $ul = 'nav pull-right ' . $class;
  else :
    $ul = 'nav ' . $class;
  endif;

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

  if ( $fixed != 1 ) :
    $class = 'navbar navbar-static-top';
  else :
    if ( $fixedpos == 1 ) :
      $class = 'navbar navbar-fixed-bottom';
    else :
      $class = 'navbar navbar-fixed-top';
    endif;
  endif;

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

  if ($navbar_bg_opacity == '') :
    $opacity = '0';
  else:
    $opacity = (intval($navbar_bg_opacity))/100;
  endif;

  if ( $opacity != 1 && $opacity != '' ) :
    $bg = str_replace('#', '',shoestrap_getVariable( 'navbar_bg'));
    $rgb = shoestrap_get_rgb( $bg, true );
    $opacityie = str_replace('0.','',$opacity);

    $style .= '.navbar, .navbar-default {';

    if ( $opacity != 1 && $opacity != '') :
      $style .= 'background: transparent;';
      $style .= 'background: rgba('.$rgb.', '.$opacity.');';
      $style .= 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#'.$opacityie.$bg.',endColorstr=#'.$opacityie.$bg.'); ;';
    else :
      $style .= 'background: #'.$bg.';';
    endif;

    $style .= '}';

  endif;

  if ( shoestrap_getVariable( 'navbar_margin' ) != 1 ) :
    $navbar_margin = shoestrap_getVariable( 'navbar_margin' );
    $style .= '.navbar-static-top { margin-top:'. $navbar_margin .'px !important; margin-bottom:'. $navbar_margin .'px !important; }';
  endif;

  wp_add_inline_style( 'shoestrap_css', $style );
}
endif;
add_action( 'wp_enqueue_scripts', 'shoestrap_navbar_css', 101 );
