<?php

function shoestrap_css(){
  $color                  = get_theme_mod('background_color');
  $variation              = get_theme_mod('shoestrap_text_variation');
  $header_bg_color        = get_theme_mod('shoestrap_header_backgroundcolor');
  $header_sitename_color  = get_theme_mod('shoestrap_header_textcolor');
  $btn_color              = '#0066cc'; //Set default to #06c
  $btn_color              = get_theme_mod('shoestrap_buttons_color');    
  $link_color             = get_theme_mod('shoestrap_link_color');
  $footer_color           = get_theme_mod('shoestrap_footer_background_color');
  $webfont                = get_theme_mod('shoestrap_google_webfonts');
  $navbar_color           = get_theme_mod('shoestrap_navbar_color');
  $sidebar_location       = get_theme_mod('shoestrap_aside_layout');
  ?>
  
  <style>
    .jumbotron{
      background: <?php echo get_theme_mod('shoestrap_hero_background_color') ?> url("<?php echo get_theme_mod( 'shoestrap_hero_background' ); ?>");
      color: <?php echo get_theme_mod('shoestrap_hero_textcolor'); ?>
    }
    <?php if ($sidebar_location == 'left'){ ?>
      #main{
        float: right;
      }
    <?php } ?>
    .navbar-inner, .navbar-inner ul.dropdown-menu{
      background-color: <?php echo $navbar_color; ?> !important;
      background-image: -moz-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>) !important;
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(<?php echo $navbar_color; ?>), to(<?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>)) !important;
      background-image: -webkit-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>) !important;
      background-image: -o-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>) !important;
      background-image: linear-gradient(to bottom, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>) !important;
      filter: e(%("progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)",argb(<?php echo $navbar_color; ?>),argb(<?php echo shoestrap_adjust_brightness($navbar_color, -10); ?>))) !important;
      border: 1px solid <?php echo shoestrap_adjust_brightness($navbar_color, -20); ?>;
    }
    .navbar .nav > li > .dropdown-menu::before{
      border-bottom: 7px solid <?php echo $navbar_color; ?>;
    }
    .navbar .nav > li > .dropdown-menu::after{
      border-bottom: 6px solid <?php echo $navbar_color; ?>;
    }
    .navbar-inner a, .navbar-inner .brand, .navbar .nav > li > a,
    .navbar-inner .dropdown-menu li > a,
    .navbar-inner .dropdown-menu li > a:hover, .navbar-inner .dropdown-menu li > a:focus, .navbar-inner .dropdown-submenu:hover > a{
      <?php if (shoestrap_get_brightness($navbar_color) >= 160){ ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, -160); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, 160); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness($navbar_color, -15); ?>;
    }
    .navbar-inner a:hover, .navbar-inner .brand:hover, .navbar .nav > li > a:hover{
      <?php if (shoestrap_get_brightness($navbar_color) >= 160){ ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, -200); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, 200); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness($navbar_color, -15); ?>;
    }
    .navbar .nav > .active > a, .navbar .nav > .active > a:hover, .navbar .nav > .active > a:focus{
      <?php if (shoestrap_get_brightness($navbar_color) >= 130){ ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, -180); ?>;
        background-color: <?php echo shoestrap_adjust_brightness($navbar_color, -20); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, 180); ?>;
        background-color: <?php echo shoestrap_adjust_brightness($navbar_color, 30); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness($navbar_color, -15); ?>;
    }
    .navbar .nav li.dropdown.open > .dropdown-toggle, .navbar .nav li.dropdown.active > .dropdown-toggle, .navbar .nav li.dropdown.open.active > .dropdown-toggle{
      <?php if (shoestrap_get_brightness($navbar_color) >= 130){ ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, -180); ?>;
        background-color: <?php echo shoestrap_adjust_brightness($navbar_color, -40); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness($navbar_color, 180); ?>;
        background-color: <?php echo shoestrap_adjust_brightness($navbar_color, 50); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness($navbar_color, -15); ?>;
    }
    .navbar .nav li.dropdown > .dropdown-toggle .caret,
    .navbar .nav li.dropdown.open > .dropdown-toggle .caret, .navbar .nav li.dropdown.active > .dropdown-toggle .caret, 
    .navbar .nav li.dropdown.open.active > .dropdown-toggle .caret{
      <?php if (shoestrap_get_brightness($navbar_color) >= 160){ ?>
        border-top-color: <?php echo shoestrap_adjust_brightness($navbar_color, -160); ?>;
        border-bottom-color: <?php echo shoestrap_adjust_brightness($navbar_color, -160); ?>;
      <?php } else { ?>
        border-top-color: <?php echo shoestrap_adjust_brightness($navbar_color, 160); ?>;
        border-bottom-color: <?php echo shoestrap_adjust_brightness($navbar_color, 160); ?>;
      <?php } ?>
    }
    a, a.active, a:hover, a.hover, a.visited, a:visited, a.link, a:link, .product-single .mp_product_meta .mp_product_price, #product_list .product .mp_product_price{color: <?php echo $link_color; ?>}
    a.btn{color: #333;}
    a.btn-primary, a.btn-info, a.btn-success, a.btn-danger, a.btn-inverse, a.btn-warning{color: #fff;}
    .dropdown-menu{background: <?php echo $color; ?>; background: #<?php echo $color; ?>;}
    <?php if (shoestrap_get_brightness($color) >= 130){ ?>
      .dropdown-menu li > a{color: #222;}
    <?php } ?>
    .dropdown-menu .active > a, .dropdown-menu .active > a:hover{
      <?php if (shoestrap_get_brightness($navbar_color) >= 160){ ?>
        background: <?php echo shoestrap_adjust_brightness($navbar_color, -100); ?>;
        color: <?php echo shoestrap_adjust_brightness($navbar_color, 10); ?> !important;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness($navbar_color, 100); ?>;
        color: <?php echo shoestrap_adjust_brightness($navbar_color, -10); ?> !important;
      <?php } ?>
    }
    .dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a{
      <?php if (shoestrap_get_brightness($navbar_color) >= 160){ ?>
        background: <?php echo shoestrap_adjust_brightness($navbar_color, -30); ?>;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness($navbar_color, 30); ?>;
      <?php } ?>
    }
    <?php if (shoestrap_get_brightness($header_bg_color) >= 130){ ?>
      .dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a{color: #222;}
    <?php } ?>
    .logo-wrapper{background: <?php echo $header_bg_color; ?>;}
    .logo-wrapper .logo a{color: <?php echo $header_sitename_color; ?>;}
    #wrap{background: <?php echo $color; ?>; background: #<?php echo $color; ?>;}
    <?php
    if ($variation == 'light') { ?>
      #wrap{color: #f7f7f7;}
      a{color: #f2f2f2;}
      .subnav{background: none;}
      .sidenav > li > a:hover{color: #fff;}
      .pager a{color: #fff; background: #222; border: 0;}
      .pager a:hover{color: #f2f2f2; background: #1d1d1d;}
    <?php } ?>
    #footer-wrapper{background: <?php echo $footer_color; ?>}
    <?php
    if (shoestrap_get_brightness($footer_color) <= 160){
      echo '#footer-wrapper{color: #dedede;}';
      if (shoestrap_get_brightness($link_color) <= 160){
        echo '#footer-wrapper a{color: #fff;}';
      }
    } ?>
    body, input, button, select, textarea, .search-query, .product-single .mp_product_meta .mp_product_price{
      font-family: '<?php echo $webfont; ?>';
    }

    <?php
    $less = new lessc;
    
    $less->setVariables(array(
        "btnColor"  => $btn_color,
    ));
    $less->setFormatter("compressed");
    
    if (shoestrap_get_brightness($btn_color) <= 160){
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
        .btn-primary{
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
        .btn-primary{
          .buttonBackground(@btnColor, @btnColorHighlight);
        }
      ");
    }?>
  </style>

  <?php if ( function_exists( 'shoestrap_typography' ) ) { shoestrap_typography(); } ?>

  <?php
}
add_action( 'wp_head', 'shoestrap_css', 199 );
