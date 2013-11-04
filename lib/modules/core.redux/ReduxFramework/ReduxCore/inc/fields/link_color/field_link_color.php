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
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_link_color' ) ) {

    /**
     * Main ReduxFramework_link_color class
     *
     * @since       1.0.0
     */
    class ReduxFramework_link_color extends ReduxFramework {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $field = array(), $value ='', $parent ) {
        
            parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );

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
                'show_regular' => true,
                'show_hover' => true,
                'show_active' => true
            );
            $this->field = wp_parse_args( $this->field, $defaults );

            $defaults = array(
                'regular' => '',
                'hover' => '',
                'active' => ''
            );

            $this->value = wp_parse_args( $this->value, $defaults );
            $this->field['default'] = wp_parse_args( $this->field['default'], $defaults );

            if ($this->field['show_regular'] === true):

            echo '<strong>' . __( 'Regular', 'redux-framework' ) . '</strong>&nbsp;<input id="' . $this->field['id'] . '-regular" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][regular]" value="'.$this->value['regular'].'" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" data-default-color="' . $this->field['default']['regular'] . '" />&nbsp;&nbsp;&nbsp;&nbsp;';

            endif;

            if ($this->field['show_hover'] === true):

            echo '<strong>' . __( 'Hover', 'redux-framework' ) . '</strong>&nbsp;<input id="' . $this->field['id'] . '-hover" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][hover]" value="' . $this->value['hover'] . '" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" data-default-color="' . $this->field['default']['hover'] . '" />&nbsp;&nbsp;&nbsp;&nbsp;';

            endif;

            if ($this->field['show_active'] === true):

            echo '<strong>' . __( 'Active', 'redux-framework' ) . '</strong>&nbsp;<input id="' . $this->field['id'] . '-active" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][active]" value="' . $this->value['active'] . '" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" data-default-color="' . $this->field['default']['active'] . '" />';

            endif;
        
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
                ReduxFramework::$_url . 'inc/fields/color/field_color.min.js', 
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
?>