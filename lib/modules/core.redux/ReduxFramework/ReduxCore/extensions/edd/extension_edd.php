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
      protected $redux;
      public $extension_url;
      public $extension_dir;

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

        if ( empty( $this->extension_dir ) ) {
          $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
        }
        

        if ( isset( $parent->args['edd'] ) && !empty( $parent->args['edd'] ) ) {
          // Create defaults array
          $defaults = array(
              'mode' => '',
              'path' => '',
              'remote_api_url' => '',
              'version' => '',
              'item_name' => '',
              'author' => '',
              'mode' => '',
              'field_id' => ''
            );

          $edd = wp_parse_args( $parent->args['edd'], $defaults );          
          
          if ( $edd['mode'] == "template" && !empty( $edd['field_id'] ) ) {
            if( !class_exists( 'EDD_SL_Theme_Updater' ) ) :
              include_once( dirname( __FILE__ ) . '/EDD_SL_Theme_Updater.php' );
            endif;

            $edd_updater = new EDD_SL_Theme_Updater(
              array(
                'remote_api_url'  => $edd['remote_api_url'],       // our store URL that is running EDD
                'version'         => $edd['version'],  // current version number
                'license'         => $parent->options[$edd['field_id']], // license key
                'item_name'       => $edd['item_name'],      // name of this theme
                'author'          => $edd['author']    // author of this theme
              )
            );            
          }
          if ( $parent->args['edd']['mode'] == "plugin" && !empty( $edd['field_id'] ) ) {
            if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) :
              include_once( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
            endif;            
          }
        }


        add_filter( 'redux/field/class/edd', array( &$this, 'overload_edd_field_path' ) ); // Adds the local field

        add_action( 'wp_ajax_redux_edd_'.$parent->args['opt_name'].'_license', array( &$this, 'license_call' ) );

      }

      function license_call() {
        
        global $wp_version;

        if ($_POST['data']['license'] == "") {
          die(-1);
        }

        $api_params = array(
          'edd_action'  => $_POST['data']['edd_action'],
          'license'     => $_POST['data']['license'],
          'item_name'   => urlencode( $_POST['data']['item_name'] )
        );



        if ( !isset( $_POST['data']['remote_api_url'] ) || empty( $_POST['data']['remote_api_url'] ) ) {
          $_POST['data']['remote_api_url'] = 'http://easydigitaldownloads.com';
        }

        $response = wp_remote_get( add_query_arg( $api_params, $_POST['data']['remote_api_url'] ), array( 'timeout' => 15, 'sslverify' => false ) );

        if ( is_wp_error( $response ) )
          return false;

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        $options[$_POST['data']['field_id']]['license'] = $_POST['data']['license'];
        $options[$_POST['data']['field_id']]['status'] = $license_data->license;

        update_option($_POST['data']['opt_name'], $options);

        if( $license_data->license == 'deactivated' ) {
          echo json_encode(array('status'=>'deactivated'));  
          update_option($_POST['data']['opt_name'], $options);
          // Delete from the DB
          die();
        } else if( $license_data->license == 'valid' ) {
          echo json_encode(array('status'=>'valid'));
          // Save to DB and update status
          die();
          // this license is still valid
        } else {
          // Change status
          update_option($_POST['data']['opt_name'], $options);
          echo json_encode(array('status'=>'invalid'));
          die();
          // this license is no longer valid
        } 

        die(-1);
      }

      // Forces the use of the embeded field path vs what the core typically would use    
      public function overload_edd_field_path($field) {
        print_r($field);


        return dirname(__FILE__).'/field_edd.php';
      }


    } // class
} // if
