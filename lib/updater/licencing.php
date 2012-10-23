<?php

define( 'EDD_SL_STORE_URL',   'http://bootstrap-commerce.com/downloads' );
define( 'EDD_SL_THEME_NAME',  'Shoestrap' );

if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
  include( dirname( __FILE__ ) . '/theme_updater.php' );
}
 
// retrieve our license key from the DB
$license_key = trim( get_option( 'shoestrap_license_key' ) );
 
// setup the updater
$edd_updater = new EDD_SL_Theme_Updater( array( 
    'remote_api_url'  => EDD_SL_STORE_URL,          // our store URL that is running EDD
    'version'         => '0.7.7',                   // current version number
    'license'         => $license_key,              // license key (used get_option above to retrieve from DB)
    'item_name'       => EDD_SL_THEME_NAME,         // name of this plugin
    'author'          => 'Aristeides Stathopoulos'  // author of this plugin
  )
);

add_action('admin_menu', 'shoestrap_license_menu');
function shoestrap_license_menu() {
  add_theme_page( 'Shoestrap Theme License', 'Shoestrap Theme License', 'manage_options', 'shoestrap-license', 'shoestrap_license_page' );
}

function shoestrap_license_page() {
  $license  = get_option( 'shoestrap_license_key' );
  $status   = get_option( 'shoestrap_license_key_status' );
  ?>
  <div class="wrap">
    <h2><?php _e('Theme License Options'); ?></h2>
    <form method="post" action="options.php">
    
      <?php settings_fields('shoestrap_license'); ?>
      
      <table class="form-table">
        <tbody>
          <tr valign="top"> 
            <th scope="row" valign="top">
              <?php _e('License Key'); ?>
            </th>
            <td>
              <input id="shoestrap_license_key" name="shoestrap_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
              <label class="description" for="shoestrap_license_key"><?php _e('Enter your license key'); ?></label>
            </td>
          </tr>
          <?php if( false !== $license ) { ?>
            <tr valign="top"> 
              <th scope="row" valign="top">
                <?php _e('Activate License'); ?>
              </th>
              <td>
                <?php if( $status !== false && $status == 'valid' ) { ?>
                  <span style="color:green;"><?php _e('active'); ?></span>
                <?php } else {
                  wp_nonce_field( 'bootstrap_commerce_nonce', 'bootstrap_commerce_nonce' ); ?>
                  <input type="submit" class="button-secondary" name="edd_theme_license_activate" value="<?php _e('Activate License'); ?>"/>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>  
      <?php submit_button(); ?>
    
    </form>
  <?php
}

add_action('admin_init', 'shoestrap_register_option');
function shoestrap_register_option() {
  // creates our settings in the options table
  register_setting('shoestrap_license', 'shoestrap_license_key', 'edd_theme_sanitize_license' );
}

function edd_theme_sanitize_license( $new ) {
  $old = get_option( 'shoestrap_license_key' );
  if( $old && $old != $new ) {
    delete_option( 'shoestrap_license_key_status' ); // new license has been entered, so must reactivate
  }
  return $new;
}

function shoestrap_activate_license() {

  if( isset( $_POST['edd_theme_license_activate'] ) ) { 
    if( ! check_admin_referer( 'bootstrap_commerce_nonce', 'bootstrap_commerce_nonce' ) )   
      return; // get out if we didn't click the Activate button

    global $wp_version;

    $license = trim( get_option( 'shoestrap_license_key' ) );
        
    $api_params = array( 
      'edd_action' => 'activate_license', 
      'license' => $license, 
      'item_name' => urlencode( EDD_SL_THEME_NAME ) 
    );
    
    $response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ) );

    if ( is_wp_error( $response ) )
      return false;

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    
    // $license_data->license will be either "active" or "inactive"

    update_option( 'shoestrap_license_key_status', $license_data->license );

  }
}
add_action('admin_init', 'shoestrap_activate_license');


function shoestrap_check_license() {

  global $wp_version;

  $license = trim( get_option( 'shoestrap_license_key' ) );
    
  $api_params = array( 
    'edd_action' => 'check_license', 
    'license' => $license, 
    'item_name' => urlencode( EDD_SL_THEME_NAME ) 
  );
  
  $response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ) );


  if ( is_wp_error( $response ) )
    return false;

  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  if( $license_data->license == 'valid' ) {
    echo 'valid'; exit;
    // this license is still valid
  } else {
    echo 'invalid'; exit;
    // this license is no longer valid
  }
}