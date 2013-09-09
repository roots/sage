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
 * @subpackage  Field_Multi_Checkbox
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_multi_checkbox' ) ) {

    /**
     * Main ReduxFramework_multi_checkbox class
     *
     * @since       1.0.0
     */
    class ReduxFramework_multi_checkbox {

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
            $this->field = $field;
            $this->value = $value;
            $this->args = $parent->args;
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

            echo '<fieldset>';

            foreach( $this->field['options'] as $k => $v ) {
                $this->value[$k] = ( isset( $this->value[$k] ) ) ? $this->value[$k] : '';
                echo '<label for="' . $this->field['id'] . '_' . array_search( $k,array_keys( $this->field['options'] ) ) . '">';
                echo '<input type="checkbox" id="' . $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" regular-text ' . $class . ' value="1" ' . checked( $this->value[$k], '1', false ) . '/>';
                echo ' ' . $v . '</label><br/>';
            }
        
            echo ( isset( $this->field['desc'] ) && !empty( $this->field['desc'] ) ) ? '<span class="description">' . $this->field['desc'] . '</span>' : '';

            echo '</fieldset>';
        }
    }
}