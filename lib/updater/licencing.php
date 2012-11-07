<?php

define( 'SHOESTRAP_SL_STORE_URL', 'http://bootstrap-commerce.com/downloads' );
define( 'SHOESTRAP_SL_THEME_NAME', 'Shoestrap' );

$license_key = trim( get_option( 'shoestrap_license_key' ) );

if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
  // load our custom theme updater
  include( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
}

$test_license = trim( get_option( 'shoestrap_license_key' ) );

$shoestrap_updater = new EDD_SL_Theme_Updater( array( 
    'remote_api_url'  => SHOESTRAP_SL_STORE_URL,
    'version'         => '1.1.3',
    'license'         => $test_license,
    'item_name'       => SHOESTRAP_SL_THEME_NAME,
    'author'          => 'Aristeides Stathopoulos'
  )
);


function shoestrap_license_menu() {
  add_theme_page( 'Theme License', 'Theme License', 'manage_options', 'themename-license', 'shoestrap_license_page' );
}
add_action('admin_menu', 'shoestrap_license_menu');

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
          <tr>
            <td colspan="2">
              <strong>This theme is an OpenSource project and is provided without any charge.</strong><br />
              If you wish to enable automatic updates, you can visit <a href="http://bootstrap-commerce.com/downloads/downloads/shoestrap/" target="_blank">this page</a>
              and get a free licence. By entering and <strong>activating</strong> it, whenever a new version is available you will be notified in your dashboard.
              If you wish to help this project, you can do so by helping out on the <a href="https://github.com/aristath/shoestrap/issues?state=open" target="_blank">github project issue queue</a> 
            </td>
          </tr>
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
                  wp_nonce_field( 'shoestrap_nonce', 'shoestrap_nonce' ); ?>
                  <input type="submit" class="button-secondary" name="shoestrap_theme_license_activate" value="<?php _e('Activate License'); ?>"/>
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

function shoestrap_register_option() {
  // creates our settings in the options table
  register_setting('shoestrap_license', 'shoestrap_license_key', 'shoestrap_theme_sanitize_license' );
}
add_action('admin_init', 'shoestrap_register_option');

function shoestrap_theme_sanitize_license( $new ) {
  $old = get_option( 'shoestrap_license_key' );
  if( $old && $old != $new ) {
    delete_option( 'shoestrap_license_key_status' ); // new license has been entered, so must reactivate
  }
  return $new;
}

function shoestrap_activate_license() {

  if( isset( $_POST['shoestrap_theme_license_activate'] ) ) { 
    if( ! check_admin_referer( 'shoestrap_nonce', 'shoestrap_nonce' ) )   
      return; // get out if we didn't click the Activate button

    global $wp_version;

    $license = trim( get_option( 'shoestrap_license_key' ) );
        
    $api_params = array( 
      'edd_action' => 'activate_license', 
      'license' => $license, 
      'item_name' => urlencode( SHOESTRAP_SL_THEME_NAME ) 
    );
    
    $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_SL_STORE_URL ) );

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
    'item_name' => urlencode( SHOESTRAP_SL_THEME_NAME ) 
  );
  
  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_SL_STORE_URL ) );


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