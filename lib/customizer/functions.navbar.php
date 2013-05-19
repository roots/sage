<?php

function shoestrap_disable_navbar() {
  if ( get_theme_mod( 'navbar_toggle' ) != 1 )
    remove_theme_support('bootstrap-top-navbar');  // Disable Bootstrap's top navbar
}
add_action( 'wp', 'shoestrap_disable_navbar' );

function shoestrap_nav_class_pull() {
  if ( get_theme_mod( 'navbar_nav_right' ) == '1' ) {
    $ul = 'nav navbar-nav pull-right';
  } else {
    $ul = 'nav navbar-nav';
  }
  return $ul;
}

/*
 * The template for the primary navbar searchbox
 */
function shoestrap_navbar_searchbox() {
  $show_searchbox = get_theme_mod( 'navbar_search' );
  if ( $show_searchbox == '1' ) { ?>
    <ul class="pull-right nav nav-collapse"><li>
    <?php do_action('shoestrap_pre_searchform'); ?>
    <form role="search" method="get" id="searchform" class="form-search navbar-search" action="<?php echo home_url('/'); ?>">
      <label class="hide" for="s"><?php _e('Search for:', 'shoestrap'); ?></label>
      <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" placeholder="<?php _e('Search', 'shoestrap'); ?> <?php bloginfo('name'); ?>">
    </form>
    <?php do_action('shoestrap_after_searchform'); ?>
    </li></ul>
    <?php
  }
}
add_action( 'shoestrap_nav_top_right', 'shoestrap_navbar_searchbox', 11 );

/*
 * The template for the navbar login link
 */
function shoestrap_navbar_login_link() {
  $primary_login_link   = get_theme_mod( 'navbar_usermenu' );

  if ( is_user_logged_in() ) {
    $link  = wp_logout_url( get_permalink() );
    $label = __( 'Logout', 'shoestrap' );
  }
  else {
    $link  = wp_login_url( get_permalink() );
    $label = __( 'Login/Register', 'shoestrap' );
  }
  $content = '<ul class="pull-right nav nav-collapse">';
  $content .= '<li class="dropdown">';
  $content .= '<a href="#" class="pull-right dropdown-toggle" data-toggle="dropdown">';
  $content .= '<i class="icon-user"></i><b class="caret"></b>';
  $content .= '<ul class="dropdown-menu">';
  $content .= '<li>';
  $content .= '<a href="' . $link . '">' . $label . '</a>';
  $content .= '</li>';
  $content .= do_action( 'shoestrap_login_link_additions' );
  $content .= '</ul>';
  $content .= '</li></ul>';
  
  if ( $primary_login_link == 1 ) {
    echo $content;
  }
}
add_action( 'shoestrap_nav_top_right', 'shoestrap_navbar_login_link', 11 );

function shoestrap_navbar_class() {
  $pos  = get_theme_mod( 'navbar_position' );

  if ( $pos == 1 )
    $class = 'navbar navbar-fixed-top';
  elseif ( $pos == 2 )
    $class = 'navbar navbar-fixed-bottom';
  else $class = 'navbar navbar-static-top';

  return $class;
}