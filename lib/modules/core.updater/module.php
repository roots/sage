<?php

/*
 * The updater core options for the Shoestrap theme
 * Simply adds the option in the Redux Framework
 */
if ( !function_exists( 'shoestrap_core_licencing_options' ) ) :
function shoestrap_core_licencing_options( $sections ) {
  // Licencing Options
  $section = array( 
    'title'     => __( 'Licencing', 'shoestrap' ),
    'icon'      => 'el-icon-repeat-alt icon-large',
  );

  $fields[] = array( 
    'name'      => __( 'Shoestrap Theme Licence', 'shoestrap' ),
    'desc'      => __( 'Enter your shoestrap licence to enable automatic updates.', 'shoestrap' ) . ' ' . shoestrap_licence_status_label(),
    'id'        => 'shoestrap_license_key',
    'default'   => '',
    'type'      => 'text',
    // 'validate_callback' => 'shoestrap_licence_callback_function',
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_licencing_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-' . REDUX_OPT_NAME, 'shoestrap_core_licencing_options', 200 ); 


// Load our theme updater
if( !class_exists( 'EDD_SL_Theme_Updater' ) ) :
  include_once( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
endif;

// setup the updater
$shoestrap_theme          = wp_get_theme();
$shoestrap_theme_version  = $shoestrap_theme->get( 'Version' );
$shoestrap_theme_author   = $shoestrap_theme->get( 'Author' );
$shoestrap_theme_name     = $shoestrap_theme->get( 'Name' );

define( 'SHOESTRAP_STORE_URL', 'http://shoestrap.org' );
define( 'SHOESTRAP_THEME_NAME', $shoestrap_theme_name );

// retrieve our license key from the DB
$license_key = trim( shoestrap_getVariable( 'shoestrap_license_key' ) );

$edd_updater = new EDD_SL_Theme_Updater(
  array(
    'remote_api_url'  => SHOESTRAP_STORE_URL,       // our store URL that is running EDD
    'version'         => $shoestrap_theme_version,  // current version number
    'license'         => $license_key,              // license key
    'item_name'       => SHOESTRAP_THEME_NAME,      // name of this theme
    'author'          => $shoestrap_theme_author    // author of this theme
  )
);

/*
 * Get the status of the licence
 */
function shoestrap_theme_license_status() {

  global $wp_version;

  $license  = shoestrap_getVariable( 'shoestrap_license_key' );

  if ( isset( $licence ) ) :
    // Copy the licence to a separate option
    update_option( 'shoestrap_license_key', $licence );
  endif;

  $api_params = array(
    'edd_action' => 'check_license',
    'license'    => $license,
    'item_name'  => urlencode( SHOESTRAP_THEME_NAME )
  );

  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );

  if ( is_wp_error( $response ) )
    return false;

  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  return $license_data->license;
}


/*
 * Activate a licence when entered
 */
function shoestrap_activate_license() {
  global $wp_version;

  $license  = shoestrap_getVariable( 'shoestrap_license_key' );

  $api_params = array(
    'edd_action' => 'activate_license',
    'license'    => $license,
    'item_name'  => urlencode( SHOESTRAP_THEME_NAME )
  );

  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

  if ( is_wp_error( $response ) ) :
    return false;
  endif;

  $license_data = json_decode( wp_remote_retrieve_body( $response ) );
}


/*
 * cache the status for 24 hours if valid
 */
function shoestrap_licence_status_cached() {
  $license  = shoestrap_getVariable( 'shoestrap_license_key' );
  $status   = get_transient( 'shoestrap_licence_status_cached' );

  if ( $status != 'valid' ) :
    if ( shoestrap_theme_license_status() == 'valid' ) :
      set_transient( 'shoestrap_licence_status_cached', shoestrap_theme_license_status(), 3600 * 24 );
    endif;
  endif;

  add_action( 'admin_init', 'shoestrap_activate_license' );
}
add_action( 'admin_init', 'shoestrap_licence_status_cached' );

/*
 *
 */
function shoestrap_licence_status_label() {
  $status   = get_transient( 'shoestrap_licence_status_cached' );
  $license  = shoestrap_getVariable( 'shoestrap_license_key' );

  $message = '';

  if ( $status == 'valid' ) :
    $message .= '<span style="background: green; color: #fff; padding: 3px 10px;">' . __( 'Valid', 'shoestrap' ) . '</span>';
  else :
    $message .= '<span style="background: red; color: #fff; padding: 3px 10px;">' . __( 'Invalid', 'shoestrap' ) . '</span>';
  endif;

  return $message;
}