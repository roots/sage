<?php

define( 'SHOESTRAP_STORE_URL', 'http://shoestrap.org' );
define( 'SHOESTRAP_THEME_NAME', 'Shoestrap 3' );
define( 'SHOESTRAP_URL', 'http://shoestrap.org/downloads/shoestrap/' );
// retrieve our license key from the DB
$license_key = trim( get_option( 'shoestrap_license_key' ) );

if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
  // load our custom theme updater
  include_once( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
}

// setup the updater
$shoestrap_theme          = wp_get_theme();
$shoestrap_theme_version  = $shoestrap_theme->get( 'Version' );
$shoestrap_theme_author   = $shoestrap_theme->get( 'Author' );

$edd_updater = new EDD_SL_Theme_Updater( array( 
  'remote_api_url'  => SHOESTRAP_STORE_URL,       // our store URL that is running EDD
  'version'         => $shoestrap_theme_version,  // current version number
  'license'         => $license_key,              // license key ( used get_option above to retrieve from DB )
  'item_name'       => SHOESTRAP_THEME_NAME,      // name of this theme
  'author'          => $shoestrap_theme_author  // author of this theme
 ) );


/*
 * The updater core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_core_licencing_options' ) ) :
function shoestrap_core_licencing_options( $sections ) {

  // Licencing Options
  $section = array( 
    'title'     => __( 'Licencing', 'shoestrap' ),
    'icon'      => 'elusive icon-repeat-alt icon-large',
  );

  $fields[] = array( 
    'name'      => __( 'Shoestrap Theme Licence', 'shoestrap' ),
    'desc'      => __( 'Enter your shoestrap licence to enable automatic updates.', 'shoestrap' ) . ' ' . shoestrap_license_key_status_indicator(),
    'id'        => 'shoestrap_license_key',
    'default'   => '',
    'type'      => 'text'
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_licencing_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-' . REDUX_OPT_NAME, 'shoestrap_core_licencing_options', 200 ); 


function shoestrap_copy_licence_to_option() {
  $new = shoestrap_getVariable( 'shoestrap_license_key' );
  $old = get_option( 'shoestrap_license_key' );

  if ( $new != $old ) :
    update_option( 'shoestrap_license_key', $new );
  endif;
}
add_action( 'init', 'shoestrap_copy_licence_to_option' );


function shoestrap_sanitize_license( $new ) {
  $old = get_option( 'shoestrap_license_key' );

  if( $old && $old != $new ) :
    // new license has been entered, so must reactivate
    update_option( 'shoestrap_license_key_status', '' );
  endif;

  return $new;
}


function shoestrap_activate_license() {
  $license_key = trim( get_option( 'shoestrap_license_key' ) );

  if ( strlen( $license_key ) < 7 ) :
    return;
  endif;

  if( get_option( 'shoestrap_license_key_status' ) == 'valid' ) :
    return;
  endif;

  $license = trim( get_option( 'shoestrap_license_key' ) );

  // data to send in our API request
  $api_params = array( 
    'edd_action'=> 'activate_license',
    'license'   => $license,
    'item_name' => urlencode( SHOESTRAP_THEME_NAME )
  );

  // Call the custom API.
  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );

  // make sure the response came back okay
  if ( is_wp_error( $response ) ) :
    return false;
  endif;

  // decode the license data
  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  update_option( 'shoestrap_license_key_status', $license_data->license );

}
add_action( 'admin_init', 'shoestrap_activate_license' );


function shoestrap_license_key_status_indicator() {
  $license  = get_option( 'shoestrap_license_key' );
  $status   = get_option( 'shoestrap_license_key_status' );
  $message = '';
  if ( false !== $license ) :
    if ( $status == 'valid' ) :
      $message = '<span style="color:#fff; background: green;">' . __( 'active', 'shoestrap' ) . '</span>';
    else :
      $message = '<span style="color:#fff; background: red;">' . __( 'inactive', 'shoestrap' ) . '</span>';
    endif;
  endif;

  return $message;
}