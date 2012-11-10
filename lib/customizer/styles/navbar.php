<?php

function shoestrap_navbar_css(){
  $header_bg_color  = get_theme_mod( 'shoestrap_header_backgroundcolor' );
  $navbar_color     = get_theme_mod( 'shoestrap_navbar_color' );
  
  // Make sure colors are properly formatted
  $header_bg_color  = '#' . str_replace( '#', '', $header_bg_color );
  $navbar_color     = '#' . str_replace( '#', '', $navbar_color );
  ?>

  <style>
    <?php if ( get_theme_mod( 'shoestrap_logo' ) ) {
      if ( get_theme_mod( 'shoestrap_header_mode' ) == 'navbar' ) { ?>
        .navbar a.brand{padding: 5px 20px 5px;}
    <?php } } ?>
    .navbar-inner, .navbar-inner ul.dropdown-menu{
      background-color: <?php echo $navbar_color; ?> !important;
      background-image: -moz-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>) !important;
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(<?php echo $navbar_color; ?>), to(<?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>)) !important;
      background-image: -webkit-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>) !important;
      background-image: -o-linear-gradient(top, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>) !important;
      background-image: linear-gradient(to bottom, <?php echo $navbar_color; ?>, <?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>) !important;
      filter: e(%("progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)",argb(<?php echo $navbar_color; ?>),argb(<?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?>))) !important;
      border: 1px solid <?php echo shoestrap_adjust_brightness( $navbar_color, -20 ); ?>;
    }
    .navbar .nav > li > .dropdown-menu::before{
      border-bottom: 7px solid <?php echo $navbar_color; ?>;
    }
    .navbar .nav > li > .dropdown-menu::after{
      border-bottom: 6px solid <?php echo $navbar_color; ?>;
    }
    .btn.btn-navbar{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, -40 ); ?>;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, 40 ); ?>;
      <?php } ?>
    }
    .btn.btn-navbar:hover, .btn.btn-navbar:active, .btn.btn-navbar:enabled{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, -30 ); ?>;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, 30 ); ?>;
      <?php } ?>
    }
    .navbar-inner a, .navbar-inner .brand, .navbar .nav > li > a,
    .navbar-inner .dropdown-menu li > a,
    .navbar-inner .dropdown-menu li > a:hover, .navbar-inner .dropdown-menu li > a:focus, .navbar-inner .dropdown-submenu:hover > a{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, -160 ); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, 160 ); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness( $navbar_color, -15 ); ?>;
    }
    .navbar-inner a:hover, .navbar-inner .brand:hover, .navbar .nav > li > a:hover{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, -200 ); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, 200 ); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness( $navbar_color, -15 ); ?>;
    }
    .navbar .nav > .active > a, .navbar .nav > .active > a:hover, .navbar .nav > .active > a:focus{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 130) { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, -180 ); ?>;
        background-color: <?php echo shoestrap_adjust_brightness( $navbar_color, -20 ); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, 180 ); ?>;
        background-color: <?php echo shoestrap_adjust_brightness( $navbar_color, 30 ); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness( $navbar_color, -15 ); ?>;
    }
    .navbar .nav li.dropdown.open > .dropdown-toggle, .navbar .nav li.dropdown.active > .dropdown-toggle, .navbar .nav li.dropdown.open.active > .dropdown-toggle{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 130) { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, -180 ); ?>;
        background-color: <?php echo shoestrap_adjust_brightness( $navbar_color, -40 ); ?>;
      <?php } else { ?>
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, 180 ); ?>;
        background-color: <?php echo shoestrap_adjust_brightness( $navbar_color, 50 ); ?>;
      <?php } ?>
      text-shadow: 0 1px 0 <?php echo shoestrap_adjust_brightness( $navbar_color, -15 ); ?>;
    }
    .navbar .nav li.dropdown > .dropdown-toggle .caret,
    .navbar .nav li.dropdown.open > .dropdown-toggle .caret, .navbar .nav li.dropdown.active > .dropdown-toggle .caret, 
    .navbar .nav li.dropdown.open.active > .dropdown-toggle .caret{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160) { ?>
        border-top-color: <?php echo shoestrap_adjust_brightness( $navbar_color, -160 ); ?>;
        border-bottom-color: <?php echo shoestrap_adjust_brightness( $navbar_color, -160 ); ?>;
      <?php } else { ?>
        border-top-color: <?php echo shoestrap_adjust_brightness( $navbar_color, 160 ); ?>;
        border-bottom-color: <?php echo shoestrap_adjust_brightness( $navbar_color, 160 ); ?>;
      <?php } ?>
    }
    .dropdown-menu .active > a, .dropdown-menu .active > a:hover{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, -100 ); ?>;
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, 10 ); ?> !important;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, 100 ); ?>;
        color: <?php echo shoestrap_adjust_brightness( $navbar_color, -10 ); ?> !important;
      <?php } ?>
    }
    .dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a{
      <?php if ( shoestrap_get_brightness( $navbar_color ) >= 160 ) { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, -30 ); ?>;
      <?php } else { ?>
        background: <?php echo shoestrap_adjust_brightness( $navbar_color, 30 ); ?>;
      <?php } ?>
    }
    <?php if ( shoestrap_get_brightness( $header_bg_color ) >= 130 ) { ?>
      .dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a{color: #222;}
    <?php } ?>

  </style>
  <?php
}
add_action( 'wp_head', 'shoestrap_navbar_css', 199 );
