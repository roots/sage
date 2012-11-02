<?php

function shoestrap_css_hero() {
  $shoestrap_header_mode            = get_theme_mod( 'shoestrap_header_mode' );
  $shoestrap_hero_background_color  = get_theme_mod( 'shoestrap_hero_background_color' );
  $shoestrap_hero_textcolor         = get_theme_mod( 'shoestrap_hero_textcolor' );
  $shoestrap_hero_background        = get_theme_mod( 'shoestrap_hero_background' );
  $shoestrap_hero_cta_color         = get_theme_mod( 'shoestrap_hero_cta_color' );
  
  if ( $shoestrap_hero_cta_color == 'default' ) {
    $shoestrap_hero_cta_color = '#ffffff';
  }
  
  // Compatibility hack for users that have upgraded from Shoestrap version 1.0.2 and below
  // and have not changed the button color. (Previously was class-based).
  if ($shoestrap_hero_cta_color == 'primary') { $shoestrap_hero_cta_color = '#0088cc'; }
  if ( $shoestrap_hero_cta_color == 'info' ) { $shoestrap_hero_cta_color = '#5bc0de'; }
  if ( $shoestrap_hero_cta_color == 'success' ) { $shoestrap_hero_cta_color = '#62c462'; }
  if ( $shoestrap_hero_cta_color == 'danger' ) { $shoestrap_hero_cta_color = '#ee5f5b'; }
  if ( $shoestrap_hero_cta_color == 'warning' ) { $shoestrap_hero_cta_color = '#f89406'; }
  if ( $shoestrap_hero_cta_color == 'inverse' ) { $shoestrap_hero_cta_color = '#444444'; }

  // Make sure colors are properly formatted
  $shoestrap_hero_background_color  = '#' . str_replace( '#', '', $shoestrap_hero_background_color );
  $shoestrap_hero_textcolor         = '#' . str_replace( '#', '', $shoestrap_hero_textcolor );
  $shoestrap_hero_cta_color         = '#' . str_replace( '#', '', $shoestrap_hero_cta_color );
  
  // if no color has been selected, set to #0066cc. This prevents errors with the php-less compiler.
  if ( strlen( $shoestrap_hero_cta_color ) < 3 ){
    $shoestrap_hero_cta_color = '#0066cc';
  } 
  ?>

  <style>
    .jumbotron{
      <?php if ( $shoestrap_header_mode == 'navbar' ) { ?>
        margin-top: -17px;
      <?php } ?>
      background: <?php echo $shoestrap_hero_background_color; ?> url("<?php echo $shoestrap_hero_background; ?>");
      color: <?php echo $shoestrap_hero_textcolor; ?>
    }

    <?php
    if ( class_exists( 'lessc' ) ) {
      $less = new lessc;
      
      $less->setVariables( array(
          "btnColor"  => $shoestrap_hero_cta_color,
      ));
      $less->setFormatter( "compressed" );
      
      if ( shoestrap_get_brightness( $shoestrap_hero_cta_color ) <= 160) {
        // The code below is a copied from bootstrap's buttons.less + mixins.less files
        echo $less->compile("
          @btnColorHighlight: darken(spin(@btnColor, 5%), 10%);
  
          .gradientBar(@primaryColor, @secondaryColor, @textColor: #fff, @textShadow: 0 -1px 0 rgba(0,0,0,.25)) {
            color: @textColor;
            text-shadow: @textShadow;
            #gradient > .vertical(@primaryColor, @secondaryColor);
            border-color: @secondaryColor @secondaryColor darken(@secondaryColor, 15%);
            border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) fadein(rgba(0,0,0,.1), 15%);
          }
  
          #gradient {
            .vertical(@startColor: #555, @endColor: #333) {
              background-color: mix(@startColor, @endColor, 60%);
              background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
              background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
              background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
              background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
              background-image: linear-gradient(to bottom, @startColor, @endColor); // Standard, IE10
              background-repeat: repeat-x;
            }
          }
  
          .buttonBackground(@startColor, @endColor, @textColor: #fff, @textShadow: 0 -1px 0 rgba(0,0,0,.25)) {
            .gradientBar(@startColor, @endColor, @textColor, @textShadow);
            *background-color: @endColor; /* Darken IE7 buttons by default so they stand out more given they won't have borders */
            .reset-filter();
            &:hover, &:active, &.active, &.disabled, &[disabled] {
              color: @textColor;
              background-color: @endColor;
              *background-color: darken(@endColor, 5%);
            }
          }
          .jumbotron .btn{
            .buttonBackground(@btnColor, @btnColorHighlight);
          }
        ");
      } else {
        echo $less->compile("
          @btnColorHighlight: darken(@btnColor, 15%);
  
          .gradientBar(@primaryColor, @secondaryColor, @textColor: #333, @textShadow: 0 -1px 0 rgba(0,0,0,.25)) {
            color: @textColor;
            text-shadow: @textShadow;
            #gradient > .vertical(@primaryColor, @secondaryColor);
            border-color: @secondaryColor @secondaryColor darken(@secondaryColor, 15%);
            border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) fadein(rgba(0,0,0,.1), 15%);
          }
  
          #gradient {
            .vertical(@startColor: #555, @endColor: #333) {
              background-color: mix(@startColor, @endColor, 60%);
              background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
              background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
              background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
              background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
              background-image: linear-gradient(to bottom, @startColor, @endColor); // Standard, IE10
              background-repeat: repeat-x;
            }
          }
  
          .buttonBackground(@startColor, @endColor, @textColor: #333, @textShadow: 0 -1px 0 rgba(0,0,0,.25)) {
            .gradientBar(@startColor, @endColor, @textColor, @textShadow);
            *background-color: @endColor; /* Darken IE7 buttons by default so they stand out more given they won't have borders */
            .reset-filter();
            &:hover, &:active, &.active, &.disabled, &[disabled] {
              color: @textColor;
              background-color: @endColor;
              *background-color: darken(@endColor, 5%);
            }
          }
          .jumbotron .btn{
            .buttonBackground(@btnColor, @btnColorHighlight);
          }
        ");
      }
    } ?>
 </style>
<?php }
add_action( 'wp_head', 'shoestrap_css_hero', 199 );