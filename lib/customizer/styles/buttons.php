<?php

function shoestrap_buttons_css() {
  $btn_color = get_theme_mod( 'shoestrap_buttons_color' );

  // Make sure colors are properly formatted
  $btn_color = '#' . str_replace( '#', '', $btn_color );
  
  // if no color has been selected, set to #0066cc. This prevents errors with the php-less compiler.
  if ( strlen( $btn_color ) < 3 ) {
    $btn_color = '#0066cc';
  } ?>

  <style>
    <?php
    if ( class_exists( 'lessc' ) ) {
      $less = new lessc;
      
      $less->setVariables( array(
          "btnColor"  => $btn_color,
      ));
      $less->setFormatter( "compressed" );
      
      if ( shoestrap_get_brightness( $btn_color ) <= 160 ) {
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
          .btn-primary, .navbar .btn-navbar{
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
          .btn, .btn-primary, .navbar .btn-navbar{
            .buttonBackground(@btnColor, @btnColorHighlight);
          }
        ");
      }
    } ?>
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_buttons_css', 199 );