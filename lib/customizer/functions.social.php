<?php

function shoestrap_navbar_social_links() {
  // An array of the available networks
  $networks   = array();
  $networks[] = array( 'url' => shoestrap_getVariable( 'fb_link' ), 'icon' => 'facebook',   'fullname' => 'Facebook' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'tw_link' ), 'icon' => 'twitter',    'fullname' => 'Twitter' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'gp_link' ), 'icon' => 'googleplus', 'fullname' => 'Google+' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'yt_link' ), 'icon' => 'youtube',    'fullname' => 'YouTube' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'li_link' ), 'icon' => 'linkedin',   'fullname' => 'Linked-In' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'pi_link' ), 'icon' => 'pinterest',  'fullname' => 'Pinterest' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'vi_link' ), 'icon' => 'vimeo',      'fullname' => 'Vimeo' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'tu_link' ), 'icon' => 'tumblr',     'fullname' => 'Tumblr' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'gi_link' ), 'icon' => 'github',     'fullname' => 'Github' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'my_link' ), 'icon' => 'myspace',    'fullname' => 'Myspace' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'vk_link' ), 'icon' => 'vkontakte',  'fullname' => 'Vkontakte' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'sc_link' ), 'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );

  // The base class for icons that will be used
  $baseclass  = 'glyphicon glyphicon-';

  // Build the content
  $content = '';
  $content .= '<ul class="nav navbar-nav pull-right">';
  $content .= '<li class="dropdown">';
  $content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
  $content .= '<i class="' . $baseclass . 'network"></i>';
  $content .= '<b class="caret"></b>';
  $content .= '</a>';
  $content .= '<ul class="dropdown-menu dropdown-social">';

  // populate the networks 
  foreach ( $networks as $network ) {
    if ( strlen( $network['url'] ) > 7 ) :
      // add thie $show variable to check if the user has actually entered a url in any of the available networks
      $show     = true;
      $content .= '<li>';
      $content .= '<a href="' . $network['url'] . '" target="_blank">';
      $content .= '<i class="' . $baseclass . $network['icon'] . '"></i>';
      $content .= $network['fullname'];
      $content .= '</a></li>';
    endif;
  }
  $content .= '</ul></li></ul>';

  // If the user has selected to show social links in the navbar, AND has entered a URL, echo the content.
  if ( shoestrap_getVariable( 'navbar_social' ) == 1 && $show == true )
    echo $content;
}
add_action( 'shoestrap_post_main_nav', 'shoestrap_navbar_social_links' );
