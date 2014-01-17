<?php


define( 'SHOESTRAP_STORE_URL',     'http://shoestrap.org' );
define( 'SHOESTRAP_THEME_NAME',    'Shoestrap 3' );
define( 'SHOESTRAP_URL',           'http://shoestrap.org/downloads/shoestrap/' );
define( 'SHOESTRAP_THEME_VERSION', '3.0.3.06' );
define( 'SHOESTRAP_THEME_AUTHOR',  'Aristeides Stathopoulos, Dimitris Kalliris, Dovy Paukstys' );

if ( !defined( 'SHOESTRAP_LICENCE_KEY' ) )
  define( 'SHOESTRAP_LICENCE_KEY', '166c0bc039afe70fb64a3a6b78e4c434' );

/*
 * Initialize the Licencing Secion.
 * This can be used by other plugins and addons to insert their licencing settings.
 */
if ( !function_exists( 'shoestrap_core_licencing_options' ) ) :
function shoestrap_core_licencing_options( $sections ) {
  // Licencing Options
  $section = array( 
    'title'     => __( 'Licencing', 'shoestrap' ),
    'icon'      => 'el-icon-repeat-alt icon-large',
  );

  $fields[] = array(
    'id'    => 'licencing-info',
    'type'  => 'info',
    'title' => __('Shoestrap Theme Licence', 'redux-framework-demo'),
    'style' => 'info',
    'desc'  => '<p>' . __( 'The Shoestrap theme no longer requires a licence for theme updates because a default licence is included.', 'shoestrap' ) . '</p><p>' . __( 'If you want to use your own licence key, you can add this line in your <code>wp-config.php</code> file:', 'shoestrap' ) . '</p><p><code>define( "SHOESTRAP_LICENCE_KEY", "YOUR_LICENCE_KEY_HERE" );</code></p>',
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_licencing_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
add_filter( 'redux/options/'.REDUX_OPT_NAME.'/sections', 'shoestrap_core_licencing_options', 200 ); 
endif;


// load our custom theme updater
if( !class_exists( 'EDD_SL_Theme_Updater' ) )
  include( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );

// setup the updater
$edd_updater = new EDD_SL_Theme_Updater( array(
  'remote_api_url'  => SHOESTRAP_STORE_URL,     // our store URL that is running EDD
  'version'         => SHOESTRAP_THEME_VERSION, // current version number
  'license'         => SHOESTRAP_LICENCE_KEY,   // license key (used get_option above to retrieve from DB)
  'item_name'       => SHOESTRAP_THEME_NAME,    // name of this theme
  'author'          => SHOESTRAP_THEME_AUTHOR   // author of this theme
));


function shoestrap_activate_license() {
  $license_key = SHOESTRAP_LICENCE_KEY;

  if( get_option( 'shoestrap_license_key_status' ) == 'valid' )
    return;

  // data to send in our API request
  $api_params = array(
    'edd_action'=> 'activate_license',
    'license'   => $license_key,
    'item_name' => urlencode( SHOESTRAP_THEME_NAME )
  );

  // Call the custom API.
  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );

  // make sure the response came back okay
  if ( is_wp_error( $response ) )
    return false;

  // decode the license data
  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  update_option( 'shoestrap_license_key_status', $license_data->license );

}
add_action( 'admin_init', 'shoestrap_activate_license' );