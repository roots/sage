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
if( !class_exists( 'ReduxFramework_extension_customizer' ) ) {


    /**
     * Main ReduxFramework customizer extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_customizer extends ReduxFramework {

      // Protected vars
      protected $redux;
      private $extension_url;
      private $extension_dir;
      private $parent;

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
        global $pagenow;
        if ($pagenow !== "customize.php" && $pagenow !== "admin-ajax.php") {
          return;
        }

        $this->parent = $parent;

        if ($parent->args['customizer'] === false) {
          return;
        }
        
        //parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );
      
        // Create defaults array
        $defaults = array();
        /*
          customize_controls_init
          customize_controls_enqueue_scripts
          customize_controls_print_styles
          customize_controls_print_scripts
          customize_controls_print_footer_scripts
        */
       


        add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue' ), 30 ); // Customizer control scripts

        add_action( 'customize_register', array( &$this, '_register_customizer_controls' ) ); // Create controls

        //add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_previewer_css' ) ); // Enqueue previewer css
        //add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_previewer_js' ) ); // Enqueue previewer javascript
        add_action( 'customize_save', array( &$this, 'customizer_save_before' ) ); // Before save
        add_action( 'customize_save_after', array( &$this, 'customizer_save_after' ) ); // After save
        if ( empty( $this->extension_dir ) ) {
          $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
          $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
        }

      }




        // All sections, settings, and controls will be added here
        public function _register_customizer_controls( $wp_customize ) {

          $order = array(
            'heading' => -500,
            'option'  => -500,
          );
          $defaults = array(
            'default-color'          => '',
            'default-image'          => '',
            'wp-head-callback'       => '',
            'admin-head-callback'    => '',
            'admin-preview-callback' => ''
          );

          foreach( $this->parent->sections as $key => $section ) {
            
            if ( empty( $section['fields'] ) ) {
              continue;
            }



            if ( empty( $section['desc'] ) && !empty( $section['subtitle'] ) ) {
              $section['desc'] = $section['subtitle'];
            }

            if ( !isset( $section['desc'] ) ) {
              $section['desc'] = "";
            }            

            if ( empty( $section['id'] ) ) {
              $section['id'] = strtolower( str_replace( " ", "", $section['title'] ) ); 
            }

            if (empty($section['priority'])) {
                $section['priority'] = $order['heading'];
                $order['heading']++;              
            }

            $wp_customize->add_section($section['id'], array(
              'title'       => $section['title'],
              'priority'    => $section['priority'],
              'description' => $section['desc']
            ));


            foreach( $section['fields'] as $skey => $option ) {

              if ( isset( $option['customizer'] ) && $option['customizer'] === false ) {
                //continue;
              }

              //Change the item priority if not set
              if ( $option['type'] != 'heading' && !isset( $option['priority'] ) ) {
                $option['priority'] = $order['option'];
                $order['option']++;
              }   

              if ( !empty( $this->options_defaults[$option['id']] ) ) {
                $option['default'] = $this->options_defaults['option']['id'];
              }

              //$option['id'] = $this->parent->args['opt_name'].'['.$option['id'].']';
              //echo $option['id'];

              if (!isset($option['default'])) {
                $option['default'] = "";
              }
              if (!isset($option['title'])) {
                $option['title'] = "";
              }


              $customSetting = array(
                'type'          => 'option',
                'capabilities'  => 'manage_theme_options',
                'default'       =>  $option['default']
              );     


              $option['id'] = $this->parent->args['opt_name'].'['.$option['id'].']';

              if ($option['type'] != "heading" || !empty($option['type'])) {
                $wp_customize->add_setting( $option['id'], $customSetting);
              }       

              if( !empty( $option['data'] ) && empty( $option['options'] ) ) {
                if (empty($option['args'])) {
                  $option['args'] = array();
                }
                if ($option['data'] == "elusive-icons" || $option['data'] == "elusive-icon" || $option['data'] == "elusive" ) {
                      $icons_file = ReduxFramework::$_dir.'inc/fields/select/elusive-icons.php';
                      $icons_file = apply_filters('redux-font-icons-file',$icons_file);
                      if(file_exists($icons_file))
                        require_once $icons_file;
                }         
                    $option['options'] = $this->parent->get_wordpress_data($option['data'], $option['args']);
              } 

              switch( $option['type'] ) {
                case 'heading':
                  // We don't want to put up the section unless it's used by something visible in the customizer
                  $section        = $option;
                  $section['id']  = strtolower( str_replace( " ", "", $option['title'] ) );
                  $order['heading']=-500;
                  if (!empty( $option['priority'] ) ) {
                    $section['priority'] = $option['priority'];
                  } else {
                    $section['priority'] = $order['heading'];
                    $order['heading']++;          
                  }
                  break;

                case 'text':
                  if (isset($option['data']) && $option['data']) {
                    continue;
                  }
                  $wp_customize->add_control( $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority'],
                    'type'    => 'text',
                  ) );
                  break;

                case 'select':
                  if ( ( isset($option['sortable']) && $option['sortable'] ) ) {
                    continue;
                  }
                  $wp_customize->add_control( $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority'],
                    'type'    => 'select',
                    'choices' => $option['options']
                  ) );
                  break;

                case 'radio':
                  //continue;
                  $wp_customize->add_control( $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority'],
                    'type'    => 'radio',
                    'choices' => $option['options']
                  ) );
                  break;

                case 'checkbox':
                  if ( ( isset($option['data']) && $option['data'] ) || ( ( isset($option['multi']) && $option['multi'] ) ) || ( ( isset($option['options']) && !empty( $option['options'] ) ) ) ) {
                    continue;
                  }
                  $wp_customize->add_control( $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority'],
                    'type'    => 'checkbox',
                  ) );
                  break;

                case 'media':
                  continue;
                  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority']
                  ) ) );
                  break;

                case 'color':
                  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority']
                  ) ) );
                  break;

                case 'switch':
                  continue;
                  $wp_customize->add_control( $option['id'], array(
                    'label'   => $option['title'],
                    'section' => $section['id'],
                    'settings'=> $option['id'],
                    'priority'=> $option['priority'],
                    'type'    => 'checkbox',
                  ) );
                  break;

                default:
                  break;
              }

            }
          }

          
               

          /*
title_tagline - Site Title & Tagline
colors - Colors
header_image - Header Image
background_image - Background Image
nav - Navigation
static_front_page - Static Front Page
          */


        }

      public function customizer_save_before($wp_customize) {

        print_r($wp_customize);

      }    

      public function customizer_save_after($wp_customize) {
//echo "there";
  //      print_r($wp_customize);
        //exit();

      }              

      /**
       * Enqueue CSS/JS for preview pane
       *
       * @since       1.0.0
       * @access      public
       * @global      $wp_styles
       * @return      void
       */
      public function _enqueue_previewer() {
        wp_enqueue_script( 'redux-extension-previewer-js', $this->extension_url . 'assets/js/preview.js' );
        $localize = array(
          'save_pending'      => __( 'You have changes that are not saved. Would you like to save them now?', 'redux-framework' ), 
          'reset_confirm'     => __( 'Are you sure? Resetting will lose all custom values.', 'redux-framework' ), 
          'preset_confirm'    => __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework' ), 
          'opt_name'          => $this->args['opt_name'],
          //'folds'       => $this->folds,
          'options'     => $this->parent->options,
          'defaults'      => $this->parent->options_defaults,
        );        
        wp_localize_script( 'redux-extension-previewer-js', 'reduxPost', $localize);
      } 

      /**
       * Enqueue CSS/JS for the customizer controls
       *
       * @since       1.0.0
       * @access      public
       * @global      $wp_styles
       * @return      void
       */
      public function _enqueue() {
        global $wp_styles;

        wp_enqueue_style( 'wp-pointer' );
        wp_enqueue_script( 'wp-pointer' );
        // Remove when code is in place!
        wp_enqueue_script('redux-extension-customizer-js', $this->extension_url . 'assets/js/customizer.js');
        // Get styles
        wp_enqueue_style('redux-extension-customizer-css', $this->extension_url . 'assets/css/customizer.css');


        $localize = array(
          'save_pending'      => __( 'You have changes that are not saved.  Would you like to save them now?', 'redux-framework' ), 
          'reset_confirm'     => __( 'Are you sure?  Resetting will lose all custom values.', 'redux-framework' ), 
          'preset_confirm'    => __( 'Your current options will be replaced with the values of this preset.  Would you like to proceed?', 'redux-framework' ), 
          'opt_name'          => $this->args['opt_name'],
          //'folds'       => $this->folds,
          'options'     => $this->parent->options,
          'defaults'      => $this->parent->options_defaults,
        );       

        // Values used by the javascript
        wp_localize_script(
            'redux-js', 
            'redux_opts', 
            $localize
        );

        do_action( 'redux-enqueue-' . $this->args['opt_name'] );

        foreach( $this->sections as $section ) {
          if( isset( $section['fields'] ) ) {
            foreach( $section['fields'] as $field ) {
              if( isset( $field['type'] ) ) {
                $field_class = 'ReduxFramework_' . $field['type'];
                if( !class_exists( $field_class ) ) {
                  $class_file = apply_filters( 'redux-typeclass-load', $this->path . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class );
                  if( $class_file ) {
                    /** @noinspection PhpIncludeInspection */
                    require_once( $class_file );
                  }
                }
                if( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
                  $enqueue = new $field_class( '', '', $this );
                  $enqueue->enqueue();
                }
              }
            }
          }
        }
      }

      /**
       * Register Option for use
       *
       * @since       1.0.0
       * @access      public
       * @return      void
       */
      public function _register_setting() {
  

      }

      /**
       * Validate the Options options before insertion
       *
       * @since       3.0.0
       * @access      public
       * @param       array $plugin_options The options array
       * @return      
       */
      public function _validate_options( $plugin_options ) {

          return $plugin_options;
      }

      /**
       * Validate values from options form (used in settings api validate function)
       * calls the custom validation class for the field so authors can override with custom classes
       *
       * @since       1.0.0
       * @access      public
       * @param       array $plugin_options
       * @param       array $options
       * @return      array $plugin_options
       */
      public function _validate_values( $plugin_options, $options ) {


          return $plugin_options;
      }

      /**
       * HTML OUTPUT.
       *
       * @since       1.0.0
       * @access      public
       * @return      void
       */
      public function _customizer_html_output() {

              
      }

    } // class
} // if
