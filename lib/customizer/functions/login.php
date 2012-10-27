<?php

/*
 * The login logo
 */
function shoestrap_login_logo() {
    if ( get_theme_mod( 'shoestrap_logo' ) ) {
      echo( get_theme_mod( 'shoestrap_logo' ) );
    }
}

/*
 * Alters the login screen according to our customizer options
 */
function shoestrap_login_scripts() {
  $color                  = get_theme_mod( 'background_color' );
  $variation              = get_theme_mod( 'shoestrap_text_variation' );
  $header_bg_color        = get_theme_mod( 'shoestrap_header_backgroundcolor' );
  $header_sitename_color  = get_theme_mod( 'shoestrap_header_textcolor' );
  $btn_color              = get_theme_mod( 'shoestrap_buttons_color' );
  $link_color             = get_theme_mod( 'shoestrap_link_color' );

  // $background is the saved custom image, or the default image.
  $background = get_background_image();

  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = get_theme_mod( 'background_color' );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = get_theme_mod( 'background_repeat', 'repeat' );
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = get_theme_mod( 'background_position_x', 'left' );
    if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
      $position = 'left';
    $position = " background-position: top $position;";

    $attachment = get_theme_mod( 'background_attachment', 'scroll' );
    if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
      $attachment = 'scroll';
    $attachment = " background-attachment: $attachment;";

    $style .= $image . $repeat . $position . $attachment;
  }

  ?>
    <style>
      .login #nav a, .login #backtoblog a, a, a.active, a:hover, a.hover, a.visited, a:visited, a.link, a:link{color: #<?php echo $link_color; ?> !important; color: <?php echo $link_color; ?> !important;}
      body.login{<?php echo trim( $style ); ?> overflow-x: hidden;}
        .login #nav, .login #backtoblog{text-shadow: none; text-shadow: 0; color: #fff;}
        body.login div#login h1 a {
            background-image: url("<?php shoestrap_login_logo(); ?>");
            background-size: contain;
            padding-bottom: 30px;
        }
    #login {
      padding: 20px;
      -webkit-border-radius: 0px 0px 4px 4px;
      border-radius: 0px 0px 4px 4px;
    }
    .login form{
      margin-left: 0;
    }
    #login h1{
      margin-left: -9999px;
      padding: 20px 9999px;
      background: <?php echo $header_bg_color; ?>;
      margin-top: -20px;
      margin-bottom: 50px;
    }
    #wp-submit {
      font-weight: normal;
      display: inline-block;
      *display: inline;
      padding: 4px 14px;
      margin-bottom: 0;
      *margin-left: .3em;
      font-size: 14px;
      line-height: 20px;
      *line-height: 20px;
      color: #333333;
      text-align: center;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
      vertical-align: middle;
      cursor: pointer;
      background-color: #f5f5f5;
      *background-color: #e6e6e6;
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
      background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
      background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
      background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
      background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
      background-repeat: repeat-x;
      border: 1px solid #bbbbbb;
      *border: 0;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      border-color: #e6e6e6 #e6e6e6 #bfbfbf;
      border-bottom-color: #a2a2a2;
      -webkit-border-radius: 4px;
         -moz-border-radius: 4px;
              border-radius: 4px;
      filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
      filter: progid:dximagetransform.microsoft.gradient(enabled=false);
      *zoom: 1;
      -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
         -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
              box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    #wp-submit:hover,
    #wp-submit:active,
    #wp-submit.active,
    #wp-submit.disabled,
    #wp-submit[disabled] {
      color: #333333;
      background-color: #e6e6e6;
      *background-color: #d9d9d9;
    }
    
    #wp-submit:active,
    #wp-submit.active {
      background-color: #cccccc \9;
    }
    
    #wp-submit:first-child {
      *margin-left: 0;
    }
    
    #wp-submit:hover {
      color: #333333;
      text-decoration: none;
      background-color: #e6e6e6;
      *background-color: #d9d9d9;
      /* Buttons in IE7 don't get borders, so darken on hover */
    
      background-position: 0 -15px;
      -webkit-transition: background-position 0.1s linear;
         -moz-transition: background-position 0.1s linear;
           -o-transition: background-position 0.1s linear;
              transition: background-position 0.1s linear;
    }
    
    #wp-submit:focus {
      outline: thin dotted #333;
      outline: 5px auto -webkit-focus-ring-color;
      outline-offset: -2px;
    }
    
    #wp-submit.active,
    #wp-submit:active {
      background-color: #e6e6e6;
      background-color: #d9d9d9 \9;
      background-image: none;
      outline: 0;
      -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
         -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
              box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    #wp-submit.disabled,
    #wp-submit[disabled] {
      cursor: default;
      background-color: #e6e6e6;
      background-image: none;
      opacity: 0.65;
      filter: alpha(opacity=65);
      -webkit-box-shadow: none;
         -moz-box-shadow: none;
              box-shadow: none;
    }
    <?php
    if ( class_exists( 'lessc' ) ) {
      $less = new lessc;
      
      $less->setVariables( array(
          "btnColor"  => $btn_color,
      ));
      $less->setFormatter( "compressed" );
      
      if ( shoestrap_get_brightness( $btn_color ) <= 160){
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
          #wp-submit.button-primary{
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
          #wp-submit.button-primary{
            .buttonBackground(@btnColor, @btnColorHighlight);
          }
        ");
      }
    }?>
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'shoestrap_login_scripts' );

/*
 * Alters the link of the login screen logo
 */
function shoestrap_login_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'shoestrap_login_url' );

