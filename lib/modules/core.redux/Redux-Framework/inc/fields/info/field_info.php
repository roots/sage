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
        
            $class = ( isset( $this->field['class'] ) ) ? ' ' . $this->field['class'] : '';     

            if( empty( $this->field['desc'] ) && !empty( $this->field['default'] ) )
                $this->field['desc'] = $this->field['default'];

            if( !isset( $this->field['fold-ids'] ) )
                $this->field['fold-ids'] = '';

            if( !isset( $this->field['fold-vals'] ) )
                $this->field['fold-vals'] = '';

            echo '</td></tr></table><div class="redux-info-field' . $class . '">';
            echo $this->field['desc'];
            echo '</div><table class="form-table no-border"><tbody><tr><th></th><td>';
        
        }
    }
}
?>