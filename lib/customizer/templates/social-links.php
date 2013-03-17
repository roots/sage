<?php

function shoestrap_add_social_links_navbar() {
  
  $mavbar_social  = get_theme_mod( 'shoestrap_navbar_social' );
  $facebook_link  = get_theme_mod( 'shoestrap_facebook_link' );
  $twitter_link   = get_theme_mod( 'shoestrap_twitter_link' );
  $gplus_link     = get_theme_mod( 'shoestrap_google_plus_link' );
  $pinterest_link = get_theme_mod( 'shoestrap_pinterest_link' );
  
  if ( $mavbar_social != 0 ) {
    echo '<ul class="nav nav-collapse pull-right">';
    if ( !empty( $facebook_link ) )   { shoestrap_social_links( 'fb' ); }
    if ( !empty( $twitter_link ) )    { shoestrap_social_links( 'tw' ); }
    if ( !empty( $gplus_link ) )      { shoestrap_social_links( 'gp' ); }
    if ( !empty( $pinterest_link ) )  { shoestrap_social_links( 'pi' ); }
    echo '</ul>';
  }
}
add_action( 'shoestrap_nav_top_right', 'shoestrap_add_social_links_navbar' );

function shoestrap_add_social_links_header() {
  
  $header_social  = get_theme_mod( 'shoestrap_header_social' );
  $facebook_link  = get_theme_mod( 'shoestrap_facebook_link' );
  $twitter_link   = get_theme_mod( 'shoestrap_twitter_link' );
  $gplus_link     = get_theme_mod( 'shoestrap_google_plus_link' );
  $pinterest_link = get_theme_mod( 'shoestrap_pinterest_link' );
  
  if ( $header_social != 0 ) {
    echo '<ul class="pull-right social-networks">';
    if ( !empty( $facebook_link ) )   { shoestrap_social_links( 'fb' ); }
    if ( !empty( $twitter_link ) )    { shoestrap_social_links( 'tw' ); }
    if ( !empty( $gplus_link ) )      { shoestrap_social_links( 'gp' ); }
    if ( !empty( $pinterest_link ) )  { shoestrap_social_links( 'pi' ); }
    echo '</ul>';
  }
}
add_action( 'shoestrap_branding_branding_right', 'shoestrap_add_social_links_header' );
