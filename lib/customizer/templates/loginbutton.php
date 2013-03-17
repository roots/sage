<?php

function shoestrap_login_button() {
    
  $show_login_link = get_theme_mod( 'shoestrap_header_loginlink' );
  
  if ( is_user_logged_in() ){
    $link  = wp_logout_url( get_permalink() );
    $label = __( 'Logout', 'shoestrap' );
  }
  else {
    $link  = wp_login_url( get_permalink() );
    $label = __( 'Login/Register', 'shoestrap' );
  }
  $content = '<ul class="pull-right nav nav-collapse"><li><a class="pull-right login-link" style="padding: 10px;" href="' . $link . '">';
  $content .= '<i class="icon-user"></i> ' . $label;
  $content .= '</a></li></ul>';
  
  if ( $show_login_link != 0 ) {
    echo $content;
  }
}
add_action( 'shoestrap_nav_top_right', 'shoestrap_login_button', 11 );
