<?php

define( 'SHOESTRAP_STORE_URL',  'http://shoestrap.org' );
define( 'SHOESTRAP_THEME_NAME', 'Shoestrap' );
define( 'SHOESTRAP_URL', 'http://shoestrap.org/downloads/shoestrap/' );
// retrieve our license key from the DB
$license_key = trim( shoestrap_getVariable( 'shoestrap_license_key' ) );

if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
  // load our custom theme updater
  include_once( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
}

// setup the updater
$edd_updater = new EDD_SL_Theme_Updater( array(
  'remote_api_url'  => SHOESTRAP_STORE_URL,       // our store URL that is running EDD
  'version'         => '3.0.0-dev-20130806',      // current version number
  'license'         => $license_key,              // license key (used get_option above to retrieve from DB)
  'item_name'       => SHOESTRAP_THEME_NAME,      // name of this theme
  'author'          => 'Aristeides Stathopoulos'  // author of this theme
));

/*
 * The updater core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_core_licencing_options' ) ) {
  function shoestrap_core_licencing_options($sections) {



    // Licencing Options
    $section = array(
    		'title' => __("Licencing", "shoestrap"),
    		'icon' => SOF_OPTIONS_URL.'img/glyphicons/glyphicons_203_lock.png',
    	);

    $fields[] = array(
      'name'      => __( 'Shoestrap Licence', 'shoestrap' ),
      'desc'      => __( 'Enter your shoestrap licence to enable automatic updates.', 'shoestrap' ),
      'id'        => 'shoestrap_license_key',
      'std'       => '',
      'type'      => 'text'
    );

    $fields[] = array(
      "name"      => "",
      "desc"      => "",
      "id"        => "shoestrap_license_key_status_indicator",
      "std"       => shoestrap_license_key_status_indicator(),
      "icon"      => true,
      "type"      => "info"
    );

    $section['fields'] = $fields;

    do_action( 'shoestrap_module_licencing_options_modifier' );
    
    array_push($sections, $section);
    return $sections;

  }
}
add_action( 'shoestrap_add_sections', 'shoestrap_core_licencing_options', 200 ); 

function shoestrap_sanitize_license( $new ) {
  $old = shoestrap_getVariable( 'shoestrap_license_key' );

  if( $old && $old != $new )
    set_theme_mod( 'shoestrap_license_key_status', '' ); // new license has been entered, so must reactivate

  return $new;
}

function shoestrap_activate_license() {
  $license_key = trim( shoestrap_getVariable( 'shoestrap_license_key' ) );

  if ( strlen( $license_key ) < 7 )
    return;

  if( shoestrap_getVariable( 'shoestrap_license_key_status' ) == 'valid' )
    return;

  $license = trim( shoestrap_getVariable( 'shoestrap_license_key' ) );

  // data to send in our API request
  $api_params = array(
    'edd_action'=> 'activate_license',
    'license'   => $license,
    'item_name' => urlencode( SHOESTRAP_THEME_NAME )
  );

  // Call the custom API.
  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );

  // make sure the response came back okay
  if ( is_wp_error( $response ) )
    return false;

  // decode the license data
  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  set_theme_mod( 'shoestrap_license_key_status', $license_data->license );

}
add_action( 'admin_init', 'shoestrap_activate_license' );

function shoestrap_license_key_status_indicator() {
  $license  = shoestrap_getVariable( 'shoestrap_license_key' );
  $status   = shoestrap_getVariable( 'shoestrap_license_key_status' );
  $message = "";
  if ( false !== $license ) :
    if ( $status !== false && $status == 'valid' )
      $message = '<span style="color:green;">' . __( 'active', 'shoestrap' ) . '</span>';
    else
      $message = '<span style="color:red;">' . __( 'inactive', 'shoestrap' ) . '</span>';
  endif;

  return $message;
}
