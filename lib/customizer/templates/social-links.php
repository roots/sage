<?php

function shoestrap_add_social_links() { ?>
  <?php
  $facebook_link  = get_theme_mod( 'shoestrap_facebook_link' );
  $twitter_link   = get_theme_mod( 'shoestrap_twitter_link' );
  $gplus_link     = get_theme_mod( 'shoestrap_google_plus_link' );
  $pinterest_link = get_theme_mod( 'shoestrap_pinterest_link' );
  
  if ( !empty( $facebook_link ) )   { shoestrap_social_links( 'fb' ); }
  if ( !empty( $twitter_link ) )    { shoestrap_social_links( 'tw' ); }
  if ( !empty( $gplus_link ) )      { shoestrap_social_links( 'gp' ); }
  if ( !empty( $pinterest_link ) )  { shoestrap_social_links( 'pi' ); }
  ?>
<?php }

$branding_mode = get_theme_mod( 'shoestrap_header_mode' );
if ( $branding_mode == 'navbar' ) {
  add_action( 'shoestrap_nav_top_right', 'shoestrap_add_social_links' );
}
