<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_edd' ) ) {


    /**
     * Main ReduxFramework customizer extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_edd extends ReduxFramework {

      // Protected vars
      protected $parent;
      public $extension_url;
      public $extension_dir;
      public static $theInstance;

      /**
       * Class Constructor. Defines the args for the extions class
       *
       * @since       1.0.0
       * @access      public
       * @param       array $sections Panel sections.
       * @param       array $args Class constructor arguments.
       * @param       array $extra_tabs Extra panel tabs.
       * @return      void
       */
      public function __construct( $parent ) {
        $this->parent = $parent;
        if ( empty( $this->extension_dir ) ) {
          $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
        }

        self::$theInstance = $this;

        add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/edd_license', array( &$this, 'overload_edd_license_field_path' ) ); // Adds the local field
        add_action( 'redux/options/'.$this->parent->args['opt_name'].'/field/edd_license/register', array( &$this, 'register' ) );
        add_action( 'wp_ajax_redux_edd_'.$parent->args['opt_name'].'_license', array( &$this, 'license_call' ) );

      }

      public function getInstance() {
        return self::$theInstance;
      }

      function register($field) {
        if ( $field['mode'] == "theme" ) {

          if ( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
            include_once( dirname( __FILE__ ) . '/edd_license/EDD_SL_Theme_Updater.php' );
          }
          if ( !empty( $this->parent->options[$field['id']]['license'] ) && ucfirst( $this->parent->options[$field['id']]['status'] ) == __('Valid', 'redux-framework') ) {
        
            $check = array( 'item_name', 'author', 'version' );
            foreach ( $check as $d ) {
              if ( !isset( $field[$d] ) || empty ( $field[$d] ) ) {
                $theme = wp_get_theme();
                $field['item_name'] = $theme->get( 'Name' );
                $field['version'] = $theme->get( 'Version' );
                $field['author'] = $theme->get( 'Author' );
                break;
              }
            }        
            $edd_updater = new EDD_SL_Theme_Updater(
              array(
                'remote_api_url'  => $field['remote_api_url'],       // our store URL that is running EDD
                'version'         => $field['version'],  // current version number
                'license'         => $this->parent->options[$field['id']]['license'], // license key
                'item_name'       => $field['item_name'], // name of this theme
                'author'          => strip_tags( $field['author'] )    // author of this theme
              )
            ); 
          }
        }
        if ( $field['mode'] == "plugin" ) {

          if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
            include_once( dirname( __FILE__ ) . '/edd_license/EDD_SL_Plugin_Updater.php' );
          }

          if ( isset( $field['path'] ) && !empty( $field['path'] ) ) {
            $check = array( 'item_name', 'author', 'version' );
            foreach ( $check as $d ) {
              if ( !isset( $field[$d] ) || empty ( $field[$d] ) ) {
                $plugin = get_plugin_data( $field['path'] );
                $field['item_name'] = $plugin['Name'];
                $field['version'] = $plugin['Version'];
                $field['author'] = strip_tags( $plugin['Author'] );
                break;
              }
            }             
          }

          if ( !empty( $this->parent->options[$field['id']]['license'] ) && ucfirst( $this->parent->options[$field['id']]['status'] ) == __('Valid', 'redux-framework') ) {
            $edd_updater = new EDD_SL_Plugin_Updater( $field['remote_api_url'], $field['path'], array( 
                'version' => $field['version'], // current version number
                'license'   =>  $this->parent->options[$field['id']]['license'],    // license key (used get_option above to retrieve from DB)
                'item_name' => $field['item_name'],  // name of this plugin
                'author' => strip_tags( $field['author'] )  // author of this plugin
              )
            );
          }
        }
      }

      function license_call($array = array()) {

        global $wp_version;

        if ( !empty( $array ) ) {
          $_POST['data'] = $array;
        }

        if ($_POST['data']['license'] == "") {
          die(-1);
        }

        $api_params = array(
          'edd_action'  => $_POST['data']['edd_action'],
          'license'     => $_POST['data']['license'],
          'item_name'   => urlencode( $_POST['data']['item_name'] ),
          'version'   => urlencode( $_POST['data']['version'] ),
          'author'   => urlencode( $_POST['data']['author'] ),
        );

        if ( !isset( $_POST['data']['remote_api_url'] ) || empty( $_POST['data']['remote_api_url'] ) ) {
          $_POST['data']['remote_api_url'] = 'http://easydigitaldownloads.com';
        }
        

        $response = wp_remote_get( add_query_arg( $api_params, $_POST['data']['remote_api_url'] ), array( 'timeout' => 15, 'sslverify' => false ) );

        if ( is_wp_error( $response ) )
          return false;

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
        $options = $this->parent->options;
        $options[$_POST['data']['field_id']]['license'] = $_POST['data']['license'];
        $options[$_POST['data']['field_id']]['status'] = $license_data->license;

        update_option($_POST['data']['opt_name'], $options);

        if( $license_data->license == 'site_inactive' ) {
          $result = json_encode(array('status'=>'Not Activated', 'response'=>$license_data->license));
          set_transient( 'redux_edd_license_'.$_POST['data']['field_id'] . '_valid', __('Not Activated', 'redux-framework'), 3600 * 24 );
        } else if( $license_data->license == 'deactivated' ) {
          $result = json_encode(array('status'=>'Deactivated', 'response'=>$license_data->license));
          set_transient( 'redux_edd_license_'.$_POST['data']['field_id'] . '_valid', __('Deactivated', 'redux-framework'), 3600 * 24 );
        } else if( $license_data->license == 'valid' ) {
          $result = json_encode(array('status'=>'Valid', 'response'=>$license_data->license));
          set_transient( 'redux_edd_license_'.$_POST['data']['field_id'] . '_valid', __('Valid', 'redux-framework'), 3600 * 24 );
        } else {
          // Change status
          $result = json_encode(array('status'=>__('Not Valid', 'redux-framework'), 'response'=>$license_data->license));
          delete_transient( 'redux_edd_license_'.$_POST['data']['field_id'] . '_valid' );
        } 

        if ( empty( $array ) ) {
          if ( isset( $result ) && !empty( $result ) ) {
            echo $result;
          }          
          die();
        } else if ( isset( $result ) && !empty( $result ) ) { // Non-ajax call
          return $result;
        }
      }

      // Forces the use of the embeded field path vs what the core typically would use    
      public function overload_edd_license_field_path($field) {
        return dirname(__FILE__).'/edd_license/field_edd_license.php';
      }


    } // class
} // if
