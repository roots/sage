<?php

function shoestrap_social_share_styles() {
  $googleplus   = get_theme_mod( 'shoestrap_gplus_on_posts' );
  $facebook     = get_theme_mod( 'shoestrap_facebook_on_posts' );
  $twitter      = get_theme_mod( 'shoestrap_twitter_on_posts' );
  $linkedin     = get_theme_mod( 'shoestrap_linkedin_on_posts' );
  $pinterest    = get_theme_mod( 'shoestrap_pinterest_on_posts' );
  
  // The number of networks.
  $networks_nr = $googleplus + $facebook + $twitter + $linkedin + $pinterest;

  $btn_color = get_theme_mod( 'shoestrap_buttons_color' );

  // Make sure colors are properly formatted
  $btn_color = '#' . str_replace( '#', '', $btn_color );
  
  // if no color has been selected, set to #0066cc. This prevents errors with the php-less compiler.
  if ( strlen( $btn_color ) < 3 ) {
    $btn_color = '#0066cc';
  } ?>

  <style type="text/css">
    .sharrre .box{
      height:22px;
      display:inline-block;
      position:relative;
      padding:0px 55px 0 8px;
      -webkit-border-radius:4px;
      -moz-border-radius:4px;
      border-radius:4px;
      font-size:12px;
      float:left;
      clear:both;
      overflow:hidden;
      -webkit-transition:all 0.3s linear;
      -moz-transition:all 0.3s linear;
      -o-transition:all 0.3s linear;
      transition:all 0.3s linear;
    }
    .sharrre .left{
      line-height:22px;
      display:block;
      white-space:nowrap;
      text-shadow:0px 1px 1px rgba(255,255,255,0.3);
      -webkit-transition:all 0.2s linear;
      -moz-transition:all 0.2s linear;
      -o-transition:all 0.2s linear;
      transition:all 0.2s linear;
    }
    .sharrre .middle{
      position:absolute;
      height:22px;
      top:0px;
      right:30px;
      width:0px;
      white-space:nowrap;
      text-align:left;
      overflow:hidden;
      -webkit-box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
      -moz-box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
      box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
      -webkit-transition:width 0.3s linear;
      -moz-transition:width 0.3s linear;
      -o-transition:width 0.3s linear;
      transition:width 0.3s linear;
    }
    .sharrre .middle a{
      font-weight:bold;
      padding:0 9px 0 9px;
      text-align:center;
      float:left;
      line-height:22px;
      -webkit-box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
      -moz-box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
      box-shadow:-1px 0px 1px rgba(255,255,255,0.4), 1px 1px 2px rgba(0,0,0,0.2) inset;
    }
    .sharrre .right{
      position:absolute;
      right:0px;
      top:0px;
      height:100%;
      width:45px;
      text-align:center;
      line-height:22px;
    }
    .sharrre .box:hover{
      padding-right:<?php echo ( $networks_nr * 30 + 40); ?>px;
    }
    .sharrre .middle a:hover{
      text-decoration:none;
    }
    .sharrre .box:hover .middle{
      width:<?php echo ( $networks_nr * 30 ); ?>px;
    }
    <?php if ( shoestrap_get_brightness( $btn_color ) >= 160 ) { ?>
      .sharrre, .sharrre .middle a{color: #333;}
      .sharrre .middle{
        background: <?php echo shoestrap_adjust_brightness( $btn_color, -10 ); ?>;
      }
      .sharrre .right{
        background: <?php echo shoestrap_adjust_brightness( $btn_color, -150 ); ?>;
        color: <?php echo shoestrap_adjust_brightness( $btn_color, 20 ); ?>;
      }
    <?php } else { ?>
      .sharrre, .sharrre .middle a{color: #fff;}
      .sharrre .middle{
        background: <?php echo shoestrap_adjust_brightness( $btn_color, 10 ); ?>;
      }
      .sharrre .right{
        background: <?php echo shoestrap_adjust_brightness( $btn_color, 150 ); ?>;
        color: <?php echo shoestrap_adjust_brightness( $btn_color, -20 ); ?>;
      }
    <?php } ?>
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
          .sharrre .box{
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
          .sharrre .box{
            .buttonBackground(@btnColor, @btnColorHighlight);
          }
        ");
      }
    } ?>
  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_social_share_styles' );