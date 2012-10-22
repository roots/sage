<div class="span4">
  <div class="social-links pull-right">
    <?php
    $facebook_link  = get_theme_mod('shoestrap_facebook_link');
    $twitter_link   = get_theme_mod('shoestrap_twitter_link');
    $gplus_link     = get_theme_mod('shoestrap_google_plus_link');
    $pinterest_link = get_theme_mod('shoestrap_pinterest_link');
  
    if ( !empty( $facebook_link ) )   { bootstrap_commerce_social_links('fb'); }
    if ( !empty( $twitter_link ) )    { bootstrap_commerce_social_links('tw'); }
    if ( !empty( $gplus_link ) )      { bootstrap_commerce_social_links('gp'); }
    if ( !empty( $pinterest_link ) )  { bootstrap_commerce_social_links('pi'); }
    ?>
  </div>

</div>