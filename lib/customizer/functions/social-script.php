<?php

/*
 * The script for sharrre buttons.
 * For more info on configuration and other options check out http://sharrre.com/
 */
function shoestrap_social_sharrre_script() {
  $googleplus   = get_theme_mod( 'shoestrap_gplus_on_posts' );
  $facebook     = get_theme_mod( 'shoestrap_facebook_on_posts' );
  $twitter      = get_theme_mod( 'shoestrap_twitter_on_posts' );
  $linkedin     = get_theme_mod( 'shoestrap_linkedin_on_posts' );
  $pinterest    = get_theme_mod( 'shoestrap_pinterest_on_posts' );
  
  if ( $googleplus  == 1 ) { $googleplus  = 'true'; } else { $googleplus  = 'false'; }
  if ( $facebook    == 1 ) { $facebook    = 'true'; } else { $facebook    = 'false'; }
  if ( $twitter     == 1 ) { $twitter     = 'true'; } else { $twitter     = 'false'; }
  if ( $linkedin    == 1 ) { $linkedin    = 'true'; } else { $linkedin    = 'false'; }
  if ( $pinterest   == 1 ) { $pinterest   = 'true'; } else { $pinterest   = 'false'; }
  
  // $templatemode = get_theme_mod( 'shoestrap_social_links_mode' );
  $template = '<div class="box"><div class="left">' . __('Share', 'shoestrap') . '</div><div class="middle">';
  if ( $facebook == 'true' ) {
    $template .= '<a href="#" class="facebook icon-facebook"></a>';
  }
  if ( $twitter == 'true' ) {
    $template .= '<a href="#" class="twitter icon-twitter"></a>';
  }
  if ( $googleplus == 'true' ) {
    $template .= '<a href="#" class="googleplus icon-google-plus"></a>';
  }
  if ( $linkedin == 'true' ) {
    $template .= '<a href="#" class="linkedin icon-linkedin"></a>';
  }
  if ( $pinterest == 'true' ) {
    $template .= '<a href="#" class="pinterest icon-pinterest"></a>';
  }
  $template .= '</div><div class="right">{total}</div></div>';
  
  ?>
  <script>
    $(window).load(function() {
      $('.shareme').sharrre({
        share: {
          googlePlus:   <?php echo $googleplus ?>,
          facebook:     <?php echo $facebook ?>,
          twitter:      <?php echo $twitter ?>,
          linkedin:     <?php echo $linkedin ?>,
          pinterest:    <?php echo $pinterest ?>
        },
        template: '<?php echo $template; ?>',
        enableHover: false,
        enableTracking: true,
        render: function(api, options){
          <?php if ( $facebook == 'true' ) { ?>
            $(api.element).on('click', '.facebook', function() {
              api.openPopup('facebook');
            });
          <?php } if ( $googleplus == 'true' ) { ?>
            $(api.element).on('click', '.googleplus', function() {
              api.openPopup('googlePlus');
            });
          <?php } if ( $twitter == 'true' ) { ?>
            $(api.element).on('click', '.twitter', function() {
              api.openPopup('twitter');
            });
          <?php } if ( $pinterest == 'true' ) { ?>
            $(api.element).on('click', '.pinterest', function() {
              api.openPopup('pinterest');
            });
          <?php } if ( $linkedin == 'true' ) { ?>
            $(api.element).on('click', '.linkedin', function() {
              api.openPopup('linkedin');
            });
          <?php } ?>
        }
      });
    });
  </script>
  <?php
}
add_action( 'wp_head', 'shoestrap_social_sharrre_script' );
