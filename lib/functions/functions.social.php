<?php

function shoestrap_navbar_social_links() {
  // An array of the available networks
  $networks   = array();

  // Started on the new stuff, not done yet.
  $networks[] = array( 'url' => shoestrap_getVariable( 'blogger_link' ),      'icon' => 'blogger',    'fullname' => 'Blogger' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'deviantart_link' ),   'icon' => 'deviantart', 'fullname' => 'DeviantART' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'digg_link' ),         'icon' => 'digg',       'fullname' => 'Digg' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'dribbble_link' ),     'icon' => 'dribbble',   'fullname' => 'Dribbble' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'facebook_link' ),     'icon' => 'facebook',   'fullname' => 'Facebook' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'flickr_link' ),       'icon' => 'flickr',     'fullname' => 'Flickr' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'forrst_link' ),       'icon' => 'forrst',     'fullname' => 'Forrst' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'github_link' ),       'icon' => 'github',     'fullname' => 'GitHub' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'google_plus_link' ),  'icon' => 'googleplus', 'fullname' => 'Google+' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'linkedin_link' ),     'icon' => 'linkedin',   'fullname' => 'LinkedIn' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'myspace_link' ),      'icon' => 'myspace',    'fullname' => 'Myspace' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'pinterest_link' ),    'icon' => 'pinterest',  'fullname' => 'Pinterest' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'reddit_link' ),       'icon' => 'reddit',     'fullname' => 'Reddit' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'rss_link' ),          'icon' => 'rss',        'fullname' => 'RSS' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'skype_link' ),        'icon' => 'skype',      'fullname' => 'Skype' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'soundcloud_link' ),   'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'tumblr_link' ),       'icon' => 'tumblr',     'fullname' => 'Tumblr' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'twitter_link' ),      'icon' => 'twitter',    'fullname' => 'Twitter' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'vimeo_link' ),        'icon' => 'vimeo',      'fullname' => 'Vimeo' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'vkontakte' ),         'icon' => 'vkontakte',  'fullname' => 'Vkontakte' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'yahoo_link' ),        'icon' => 'yahoo',      'fullname' => 'Yahoo' );
  $networks[] = array( 'url' => shoestrap_getVariable( 'youtube_link' ),      'icon' => 'youtube',    'fullname' => 'YouTube' );

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
      // add the $show variable to check if the user has actually entered a url in any of the available networks
      $show     = true;
      $content .= '<li>';
      $content .= '<a href="' . $network['url'] . '" target="_blank">';
      $content .= '<i class="' . $baseclass . $network['icon'] . '"></i> ';
      $content .= $network['fullname'];
      $content .= '</a></li>';
    endif;
  }
  $content .= '</ul></li></ul>';

  // If the user has selected to show social links in the navbar, AND has entered a URL, echo the content.
  if ( shoestrap_getVariable( 'navbar_social' ) == 1 && $show == true ) {
    echo $content;
  }
}
add_action( 'shoestrap_post_main_nav', 'shoestrap_navbar_social_links' );

function shoestrap_social_sharing() {
  // An array of the available networks
  $networks   = array();
  $networks[] = array( 'on' => shoestrap_getVariable( 'facebook_share' ),     'icon' => 'facebook',   'fullname' => 'Facebook' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'twitter_share' ),      'icon' => 'twitter',    'fullname' => 'Twitter' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'reddit_share' ),       'icon' => 'reddit',     'fullname' => 'Reddit' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'linkedin_share' ),     'icon' => 'linkedin',   'fullname' => 'LinkedIn' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'google_plus_share' ),  'icon' => 'googleplus', 'fullname' => 'Google+' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'tumblr_share' ),       'icon' => 'tumblr',     'fullname' => 'Tumblr' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'pinterest_share' ),    'icon' => 'pinterest',  'fullname' => 'Pinterest' );
  $networks[] = array( 'on' => shoestrap_getVariable( 'email_share' ),        'icon' => 'envelope',   'fullname' => 'Email' );

  $twittername = '';

  // The base class for icons that will be used
  $baseclass  = 'glyphicon glyphicon-';

  // Build the content
  $content = '';
  $content .= '<div class="btn-group social-share">';
  $content .= '<button class="btn btn-primary btn-small">' . __( 'Share', 'shoestrap' ) . '</button>';

  foreach ( $networks as $network ) {
    if ( $network['on'] == 1 ) :
      $show     = true;

      if ( $network['icon'] == 'facebook' )
        $url    = 'http://www.facebook.com/sharer.php?u=' . get_permalink() . '&amp;title=' . get_the_title();
      elseif ( $network['icon'] == 'twitter' )
        $url    = 'http://twitter.com/home/?status=' . get_the_title() . ' - ' . get_permalink() . ' via @' . $twittername;
      elseif ( $network['icon'] == 'linkedin' )
        $url    = 'http://linkedin.com/shareArticle?mini=true&amp;url=' .get_permalink() . '&amp;title=' . get_the_title();
      elseif ( $network['icon'] == 'reddit' )
        $url    = 'http://reddit.com/submit?url=' .get_permalink() . '&amp;title=' . get_the_title();
      elseif ( $network['icon'] == 'tumblr' )
        $url    = 'http://www.tumblr.com/share/link?url=' .urlencode(get_permalink()) . '&amp;name=' . urlencode(get_the_title()) . "&amp;description=".urlencode(the_excerpt()); // Add description
      elseif ( $network['icon'] == 'envelope' )
        $url    = 'mailto:?subject=' .get_the_title() . '&amp;body=' . get_permalink();
      elseif ( $network['icon'] == 'googleplus' )
        $url    = 'https://plus.google.com/share?url=' . get_permalink();
      elseif ( $network['icon'] == 'pinterest' )
        $url    = 'http://pinterest.com/pin/create/button/?url=' . get_permalink();

      $content .= '<a class="btn btn-default btn-small" href="' . $url . '" target="_blank">';
      $content .= '<i class="' . $baseclass . $network['icon'] . '"></i>';
      $content .= '</a>';
    endif;
  }
  $content .= '</div>';

  // If the user has selected to show social links in the navbar, AND has entered a URL, echo the content.
  if ( $show == 1 )
    echo $content;
}
add_action( 'shoestrap_before_the_content', 'shoestrap_social_sharing', 5 );
