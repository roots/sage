<?php

function shoestrap_text_css() {
  $link_color = get_theme_mod( 'shoestrap_link_color' );
  $variation  = get_theme_mod( 'shoestrap_text_variation' );

  // Make sure colors are properly formatted
  $link_color = '#' . str_replace( '#', '', $link_color );
  ?>

  <style>
    a, a.active, a:hover, a.hover, a.visited, a:visited, a.link, a:link, .product-single .mp_product_meta .mp_product_price, #product_list .product .mp_product_price{ color: <?php echo $link_color; ?> }
    a.btn{ color: #333; }
    a.btn-primary, a.btn-info, a.btn-success, a.btn-danger, a.btn-inverse, a.btn-warning{ color: #fff; }
    <?php
    if ( $variation == 'light' ) { ?>
      #wrap{color: #f7f7f7;}
      a{color: #f2f2f2;}
      .subnav{background: none;}
      .sidenav > li > a:hover{color: #fff;}
      .pager a{color: #fff; background: #222; border: 0;}
      .pager a:hover{color: #f2f2f2; background: #1d1d1d;}
    <?php } ?>
  </style>

  <?php
}
add_action( 'wp_head', 'shoestrap_text_css', 199 );
