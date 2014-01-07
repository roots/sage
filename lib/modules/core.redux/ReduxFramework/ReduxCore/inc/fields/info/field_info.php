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
 * @subpackage  Field_Info
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_info' ) ) {

    /**
     * Main ReduxFramework_info class
     *
     * @since       1.0.0
     */
    class ReduxFramework_info extends ReduxFramework {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
        
            //parent::__construct( $parent->sections, $parent->args );
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
        
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            $defaults = array(
                'title' => '',
                'desc' => '',
            );
            $this->field = wp_parse_args( $this->field, $defaults );

        	if ( !isset( $this->field['style'] ) ) {
        		$this->field['style'] = "";
        	}

            if( empty( $this->field['desc'] ) && !empty( $this->field['default'] ) ) {
            	$this->field['desc'] = $this->field['default'];
            	unset($this->field['default']);
            }       

            if( empty( $this->field['desc'] ) && !empty( $this->field['subtitle'] ) ) {
            	$this->field['desc'] = $this->field['subtitle'];
            	unset($this->field['subtitle']);
            }         

            if ( empty( $this->field['desc'] ) ) {
            	$this->field['desc'] = "";
            }

            if( empty( $this->field['raw_html'] ) ) {
                $this->field['class'] .= ' redux-info-field';

                if( empty( $this->field['style'] ) ) {
                    $this->field['style'] = 'normal';
                }

                $this->field['style'] = 'redux-' . $this->field['style'].' ';
            }

            echo '</td></tr></table><div id="' . $this->field['id'] . '" class="' . $this->field['style'] . $this->field['class'] . '">';

            	if ( !empty($this->field['raw_html']) && $this->field['raw_html'] ) {
            		echo $this->field['desc'];
            	} else {
		            if( isset( $this->field['title'] ) && !empty( $this->field['title'] ) ) {
		                $this->field['title'] = '<b>' . $this->field['title'] . '</b><br/>';
		            }

		            if( isset( $this->field['icon'] ) && !empty( $this->field['icon'] ) && $this->field['icon'] !== true ) {
		                echo '<p class="redux-info-icon"><i class="' . $this->field['icon'] . ' icon-large"></i></p>';
		            }
                    if (isset($this->field['raw']) && !empty($this->field['raw'])) {
                        echo $this->field['raw'];    
                    }
                    if ( !empty( $this->field['title'] ) || !empty( $this->field['desc'] ) ) {
                        echo '<p class="redux-info-desc">' . $this->field['title'] . $this->field['desc'] . '</p>';    
                    }	            	

            	}

            echo '</div><table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom:0;"><th style="padding-top:0;"></th><td style="padding-top:0;">';
        
        }


        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            wp_enqueue_style(
                'redux-field-info-css',
                ReduxFramework::$_url . 'inc/fields/info/field_info.css',
                time(),
                true
            );

        }

    }
}
