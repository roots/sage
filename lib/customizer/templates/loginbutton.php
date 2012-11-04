<?php

function shoestrap_login_button() {
    
  $show_login_link = get_theme_mod( 'shoestrap_header_loginlink' );
  
  $content = '<li>';
  if ( is_user_logged_in() ){
    $link  = wp_logout_url( get_permalink() );
    $label = __( 'Logout', 'bootstrap_commerce' );
  }
  else {
    $link  = wp_login_url( get_permalink() );
    $label = __( 'Login/Register', 'bootstrap_commerce' );
  }
  $content .= '<a href="' . $link . '">';
  $content .= '<i class="icon-user"></i> ' . $label;
  $content .= '</a>';
  $content .= '</li>';
  
  if ( $show_login_link != '0' ) {
    echo $content;
  }
}
add_action( 'shoestrap_nav_top_right', 'shoestrap_login_button', 8 );
