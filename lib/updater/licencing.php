<?php

define( 'SHOESTRAP_STORE_URL',  'http://bootstrap-commerce.com/downloads' );
define( 'SHOESTRAP_THEME_NAME', 'Shoestrap' );

// retrieve our license key from the DB
$license_key = trim( get_option( 'shoestrap_license_key' ) );
 
// setup the updater
$edd_updater = new Shoestrap_Theme_Updater( array(
  'remote_api_url'  => SHOESTRAP_STORE_URL,       // our store URL that is running EDD
  'version'         => '1.1.1',                   // current version number
  'license'         => $license_key,              // license key (used get_option above to retrieve from DB)
  'item_name'       => SHOESTRAP_THEME_NAME,      // name of this plugin
  'author'          => 'Aristeides Stathopoulos'  // author of this plugin
));

add_action( 'admin_menu', 'shoestrap_license_menu' );
function shoestrap_license_menu() {
  add_theme_page( 'Shoestrap Theme License', 'Shoestrap Theme License', 'manage_options', 'shoestrap-license', 'shoestrap_license_page' );
}

function shoestrap_license_page() {
  $license  = get_option( 'shoestrap_license_key' );
  $status   = get_option( 'shoestrap_license_key_status' );
  ?>
  <div class="wrap">
    <h2><?php _e( 'Shoestrap Theme License' ); ?></h2>
    <form method="post" action="options.php">
    
      <?php settings_fields( 'shoestrap_license' ); ?>
      
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
              <?php _e( 'License Key' ); ?>
            </th>
            <td>
              <input id="shoestrap_license_key" name="shoestrap_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
              <label class="description" for="shoestrap_license_key"><?php _e( 'Enter your license key' ); ?></label>
            </td>
          </tr>
          <?php if( false !== $license ) { ?>
            <tr valign="top"> 
              <th scope="row" valign="top">
                <?php _e( 'Activate License' ); ?>
              </th>
              <td>
                <?php if( $status !== false && $status == 'valid' ) { ?>
                  <span style="color:green;"><?php _e( 'active' ); ?></span>
                <?php } else {
                  wp_nonce_field( 'bootstrap_commerce_nonce', 'bootstrap_commerce_nonce' ); ?>
                  <input type="submit" class="button-secondary" name="edd_theme_license_activate" value="<?php _e( 'Activate License' ); ?>"/>
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

add_action( 'admin_init', 'shoestrap_register_option' );
function shoestrap_register_option() {
  // creates our settings in the options table
  register_setting( 'shoestrap_license', 'shoestrap_license_key', 'edd_theme_sanitize_license' );
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
      'item_name' => urlencode( SHOESTRAP_THEME_NAME ) 
    );
    
    $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );

    if ( is_wp_error( $response ) )
      return false;

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    
    // $license_data->license will be either "active" or "inactive"

    update_option( 'shoestrap_license_key_status', $license_data->license );

  }
}
add_action( 'admin_init', 'shoestrap_activate_license' );


function shoestrap_check_license() {

  global $wp_version;

  $license = trim( get_option( 'shoestrap_license_key' ) );
    
  $api_params = array( 
    'edd_action' => 'check_license', 
    'license' => $license, 
    'item_name' => urlencode( SHOESTRAP_THEME_NAME ) 
  );
  
  $response = wp_remote_get( add_query_arg( $api_params, SHOESTRAP_STORE_URL ) );


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

class Shoestrap_Theme_Updater {
  private $remote_api_url;
  private $request_data;
  private $response_key;
  private $theme_slug;
  private $license_key;
  private $version;
  private $author;
  
  function __construct( $args = array() ) {
    $args = wp_parse_args( $args, array(
      'remote_api_url' => 'http://aristeides.com',
      'request_data' => array(),
      'theme_slug' => get_template(),
      'item_name' => '',
      'license' => '',
      'version' => '',
      'author' => ''
    ) );
    extract( $args );
    
    $this->license = $license;
    $this->item_name = $item_name;
    $this->version = $version;
    $this->theme_slug = sanitize_key( $theme_slug );
    $this->author = $author;
    $this->remote_api_url = $remote_api_url;
    $this->response_key = $this->theme_slug . '-update-response';
    
    
    add_filter( 'site_transient_update_themes', array( &$this, 'theme_update_transient' ) );
    add_filter( 'delete_site_transient_update_themes', array( &$this, 'delete_theme_update_transient' ) );
    add_action( 'load-update-core.php', array( &$this, 'delete_theme_update_transient' ) );
    add_action( 'load-themes.php', array( &$this, 'delete_theme_update_transient' ) );
    add_action( 'load-themes.php', array( &$this, 'load_themes_screen' ) );
  }
  
  function load_themes_screen() {
    add_thickbox();
    add_action( 'admin_notices', array( &$this, 'update_nag' ) );
  }
  
  function update_nag() {
    $theme = wp_get_theme( $this->theme_slug );
    
    $api_response = get_transient( $this->response_key );
    
    if( false === $api_response )
      return;

    $update_url = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $this->theme_slug ), 'upgrade-theme_' . $this->theme_slug );
    $update_onclick = ' onclick="if ( confirm(\'' . esc_js( __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update." ) ) . '\') ) {return true;}return false;"';
    
    if ( version_compare( $theme->get( 'Version' ), $api_response->new_version, '<' ) ) {

      echo '<div id="update-nag">';
        printf( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.',
          $theme->get( 'Name' ),
          $api_response->new_version,
          '#TB_inline?width=640&amp;inlineId=' . $this->theme_slug . '_changelog',
          $theme->get( 'Name' ),
          $update_url,
          $update_onclick
        );
      echo '</div>';
      echo '<div id="' . $this->theme_slug . '_' . 'changelog" style="display:none;">';
        echo wpautop( $api_response->sections['changelog'] );
      echo '</div>';
    }
  }
  
  function theme_update_transient( $value ) {
    $update_data = $this->check_for_update();
    if ( $update_data ) {
      $value->response[ $this->theme_slug ] = $update_data;
    }
    return $value;
  }
  
  function delete_theme_update_transient() {
    delete_transient( $this->response_key );
  }
  
  function check_for_update() {

    $theme = wp_get_theme( $this->theme_slug );
    
    $update_data = get_transient( $this->response_key );
    if ( false === $update_data ) {
      $failed = false;
      
      $api_params = array( 
        'edd_action'  => 'get_version',
        'license'     => $this->license, 
        'name'      => $this->item_name,
        'slug'      => $this->theme_slug,
        'author'    => $this->author
      );

      $response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'body' => $api_params ) );
      
      // make sure the response was successful
      if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
        $failed = true;
      }
      
      $update_data = json_decode( wp_remote_retrieve_body( $response ) );

      if ( ! is_object( $update_data ) ) {
        $failed = true;
      }
      
      // if the response failed, try again in 30 minutes
      if ( $failed ) {
        $data = new stdClass;
        $data->new_version = $theme->get( 'Version' );
        set_transient( $this->response_key, $data, strtotime( '+30 minutes' ) );
        return false;
      }
      
      // if the status is 'ok', return the update arguments
      if ( ! $failed ) {
        $update_data->sections = maybe_unserialize( $update_data->sections );
        set_transient( $this->response_key, $update_data, strtotime( '+12 hours' ) );
      }
    }
    
    if ( version_compare( $theme->get( 'Version' ), $update_data->new_version, '>=' ) ) {
      return false;
    }
    
    return (array) $update_data;
  }
}