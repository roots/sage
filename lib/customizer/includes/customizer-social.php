<?php

function shoestrap_social_links( $network = '' ) {
  
  $facebook_link  = get_theme_mod('shoestrap_facebook_link');
  $twitter_link   = get_theme_mod('shoestrap_twitter_link');
  $gplus_link     = get_theme_mod('shoestrap_google_plus_link');
  $pinterest_link = get_theme_mod('shoestrap_pinterest_link');
  
  if ( $network == 'fb' ){ ?>
    <a href="<?php echo $facebook_link; ?>" target="_blank"><i class="icon-facebook-sign"></i></a>
  <?php }
  elseif ( $network == 'tw' ) { ?>
    <a href="<?php echo $twitter_link; ?>" target="_blank"><i class="icon-twitter-sign"></i></a>
  <?php }
  elseif ( $network == 'gp' ) { ?>
    <a href="<?php echo $gplus_link; ?>" target="_blank"><i class="icon-google-plus-sign"></i></a>
  <?php }
  elseif ( $network == 'pi' ) { ?>
    <a href="<?php echo $pinterest_link; ?>" target="_blank"><i class="icon-pinterest-sign"></i></a>
  <?php }
}

if ( get_theme_mod( 'shoestrap_header_mode' ) == 'navbar' ) {
  add_action( 'shoestrap_nav_top_right', 'shoestrap_social_links' );
}