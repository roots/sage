<?php

/**
 * Include the EUAPI if it's not already present.
 */
if ( !function_exists( 'euapi_autoloader' ) ) :
  include_once( dirname(__FILE__) . '/external-update-api/external-update-api.php' );
endif;

/**
 * Hooks into the EUAPI update mechanism and tells it to fetch Babble updates from GitHub.
 *
 * @param  EUAPI_Handler|null $handler Usually null. Can be an EUAPI_Handler object if one has been set.
 * @param  EUAPI_Item         $item    An EUAPI_Item for the current plugin.
 * @return EUAPI_Handler|null          An EUAPI_Handler if we're overriding updates for this plugin, null if not.
 */
function shoestrap_euapi_theme_handler( EUAPI_Handler $handler = null, EUAPI_Item $item ) {
  if ( 'http://shoestrap.org/' == $item->url ) {

    $handler = new EUAPI_Handler_GitHub( array(
      'type'       => $item->type,
      'file'       => $item->file,
      'github_url' => 'https://github.com/shoestrap/shoestrap',
      'sslverify'  => false,
    ) );

  }

  return $handler;
}
add_filter( 'euapi_theme_handler', 'shoestrap_euapi_theme_handler', 10, 2 );