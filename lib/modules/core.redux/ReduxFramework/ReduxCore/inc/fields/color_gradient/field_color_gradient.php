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
 * @subpackage  Field_Color_Gradient
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_color_gradient' ) ) {

    /**
     * Main ReduxFramework_color_gradient class
     *
     * @since       1.0.0
     */
    class ReduxFramework_color_gradient extends ReduxFramework {
    
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
        
            // No errors please
            $defaults = array(
                'from' => '',
                'to' => ''
            );

            $this->value = wp_parse_args( $this->value, $defaults );

            echo '<strong>' . __( 'From ', 'redux-framework' ) . '</strong>&nbsp;';
            echo '<input data-id="'.$this->field['id'].'" id="' . $this->field['id'] . '-from" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][from]" value="'.$this->value['from'].'" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" data-default-color="' . $this->field['default']['from'] . '" />';

			if ( !isset( $this->field['transparent'] ) || $this->field['transparent'] !== false ) {
				$tChecked = "";
				if ( $this->value['from'] == "transparent" ) {
					$tChecked = ' checked="checked"';
				}
	            echo '<label for="' . $this->field['id'] . '-from-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . $this->field['class'] . '" id="' . $this->field['id'] . '-from-transparency" data-id="' . $this->field['id'] . '-from" value="1"'.$tChecked.'> '.__('Transparent', 'redux-framework').'</label>';
	        }

            echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . __( 'To ', 'redux-framework' ) . '</strong>&nbsp;<input data-id="'.$this->field['id'].'" id="' . $this->field['id'] . '-to" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][to]" value="' . $this->value['to'] . '" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" data-default-color="' . $this->field['default']['to'] . '" />';
        	
        	if ( !isset( $this->field['transparent'] ) || $this->field['transparent'] !== false ) {
				$tChecked = "";
				if ( $this->value['from'] == "transparent" ) {
					$tChecked = ' checked="checked"';
				}
				echo '<label for="' . $this->field['id'] . '-to-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency" id="' . $this->field['id'] . '-to-transparency" data-id="' . $this->field['id'] . '-to" value="1"'.$tChecked.'> '.__('Transparent', 'redux-framework').'</label>';	
			}

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
        
            wp_enqueue_script(
                'redux-field-color-js', 
                ReduxFramework::$_url . 'inc/fields/color/field_color.js', 
                array( 'jquery', 'wp-color-picker' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-color-js', 
                ReduxFramework::$_url . 'inc/fields/color/field_color.css', 
                time(),
                true
            ); 

			wp_enqueue_style(
				'redux-field-color-css', 
				ReduxFramework::$_url . 'inc/fields/color/field_color.css', 
				time(),
				true
			);                 
        
        }
    }
}