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
      public $url;
      public $dir;

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

        if ( empty( $this->dir ) ) {
          $this->dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $this->url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->dir ) );
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
                'remote_api_url'  => $edd['remote'],       // our store URL that is running EDD
                'version'         => $edd['version'],  // current version number
                'license'         => $parent->options[$edd['field_id']],              // license key
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

        add_action( 'wp_ajax_redux_edd_'.$parent->args['opt_name'].'_verify_license', array( &$this, 'ajax_verify_license' ) );

      }

      function ajax_verify_license() {
        
        // Fill whatever you need here! All args and license/status from the field are set from the $args array.
        // You'll need to initate a save if it worked properly to keep the value or update the DB. Get the rest
        // of the code in and I can do that.

        // Everything is in $_POST['data']

        die();
      }


      // Forces the use of the embeded field path vs what the core typically would use    
      public function overload_edd_field_path($field) {
        return dirname(__FILE__).'/field_edd.php';
      }


    } // class
} // if
