<?php

if ( !function_exists( 'euapi_autoloader' ) ) :
  include_once( dirname(__FILE__) . '/external-update-api/updater.php' );
endif;

if ( !class_exists( 'GitHub_OAuth_Connector' ) ) :
  include_once( dirname(__FILE__) . '/external-update-api/github-oauth-connector.php' );
endif;

function shoestrap_update_handler( EUAPI_Handler $handler = null, EUAPI_Item $item ) {
  if ( 'my-plugin/my-plugin.php' == $item->file ) :
    $handler = new EUAPI_Handler_GitHub( array(
      'type'       => $item->type,
      'file'       => $item->file,
      'github_url' => 'https://github.com/shoestrap/shoestrap',
      'sslverify'  => false
    ) );
  endif;

  return $handler;
}
add_filter( 'euapi_theme_handler', 'my_update_handler', 10, 2 );