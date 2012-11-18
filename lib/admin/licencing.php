<?php

define( 'SHOESTRAP_STORE_URL',  'http://bootstrap-commerce.com/downloads' );
define( 'SHOESTRAP_THEME_NAME', 'Shoestrap' );
define( 'SHOESTRAP_URL', 'http://bootstrap-commerce.com/downloads/downloads/shoestrap/' );
// retrieve our license key from the DB
$license_key = trim( get_option( 'shoestrap_license_key' ) );
 
if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
  // load our custom theme updater
  include( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
}

// setup the updater
$edd_updater = new EDD_SL_Theme_Updater( array(
  'remote_api_url'  => SHOESTRAP_STORE_URL,       // our store URL that is running EDD
  'version'         => '1.15',                    // current version number
  'license'         => $license_key,              // license key (used get_option above to retrieve from DB)
  'item_name'       => SHOESTRAP_THEME_NAME,// name of this theme
  'author'          => 'Aristeides Stathopoulos'  // author of this theme
));

add_action( 'shoestrap_admin_content', 'shoestrap_license_page', 10 );
function shoestrap_license_page() {
  $license      = get_option( 'shoestrap_license_key' );
  $status       = get_option( 'shoestrap_license_key_status' );
  $submit_text  = __( 'Save & activate licence', 'shoestrap' );
  $current_url  = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $customizeurl = add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() );

  ?>
  <div class="postbox">
    <h3 class="hndle" style="padding: 7px 10px;"><span><?php _e( 'Shoestrap theme Licence Key', 'shoestrap' ); ?></span></h3>
    <div class="inside">

      <strong>This theme is an OpenSource project and is provided free of charge.</strong><br />
      If you wish to enable automatic updates, you can visit <a href="http://bootstrap-commerce.com/downloads/downloads/shoestrap/" target="_blank">this page</a>
      and get a free licence. By entering and <strong>activating</strong> it, whenever a new version is available you will be notified in your dashboard.
      If you wish to help this project, you can do so by helping out on the <a href="https://github.com/aristath/shoestrap" target="_blank">github project page</a> 
      <br>
      <p>To configure the options for this theme, please visit the <a href="<?php  echo $customizeurl ?>">Customizer</a></p>
      
      <form method="post" action="options.php">
        <?php settings_fields( 'shoestrap_license' ); ?>
    
        <?php _e( 'License Key:', 'shoestrap' ); ?>
    
        <input id="shoestrap_license_key" name="shoestrap_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
        <label class="description" for="shoestrap_license_key">
          <?php _e( 'Enter your license key', 'shoestrap' ); ?>
          (
          <?php if( false !== $license ) { ?>
            <?php if( $status !== false && $status == 'valid' ) { ?>
              <span style="color:green;"><?php _e( 'active', 'shoestrap' ); ?></span>
            <?php } else { ?>
              <span style="color:red;"><?php _e( 'inactive', 'shoestrap' ); ?></span>
            <?php } ?>
          <?php } ?>
          )
          
        </label>
    
        <?php submit_button( $submit_text ); ?>
    
      </form>
    </div>
  </div>
  <?php
}
add_action( 'admin_init', 'shoestrap_register_option' );
function shoestrap_register_option() {
  // creates our settings in the options table
  register_setting( 'shoestrap_license', 'shoestrap_license_key', 'shoestrap_sanitize_license' );
}

function shoestrap_sanitize_license( $new ) {
  $old = get_option( 'shoestrap_license_key' );
  if( $old && $old != $new ) {
    delete_option( 'shoestrap_license_key_status' ); // new license has been entered, so must reactivate
  }
  return $new;
}

function shoestrap_activate_license() {
  $license_key = trim( get_option( 'shoestrap_license_key' ) );
  if ( strlen( $license_key ) < 7 )
    return;
  if ( strlen( $license_key ) < 7 )
    return;

  if( get_option( 'shoestrap_license_key_status' ) == 'active' )
    return;

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
  if ( is_wp_error( $response ) )
    return false;

  // decode the license data
  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  update_option( 'shoestrap_license_key_status', $license_data->license );

}
add_action( 'admin_init', 'shoestrap_activate_license' );