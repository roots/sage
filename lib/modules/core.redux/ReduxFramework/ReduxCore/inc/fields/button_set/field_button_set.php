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
 * @subpackage  Field_Button_Set
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_button_set' ) ) {

    /**
     * Main ReduxFramework_button_set class
     *
     * @since       1.0.0
     */
    class ReduxFramework_button_set extends ReduxFramework {
    
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
        
            echo '<div class="buttonset ui-buttonset">';
            
            foreach( $this->field['options'] as $k => $v ) {
                
                echo '<input data-id="'.$this->field['id'].'" type="radio" id="'.$this->field['id'].'-buttonset'.$k.'" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" class="' . $this->field['class'] . '" value="' . $k . '" ' . checked( $this->value, $k, false ) . '/>';
                echo '<label for="'.$this->field['id'].'-buttonset'.$k.'">' . $v . '</label>';
                
            }
            
            echo '</div>';
        
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
                'redux-field-button-set-js', 
                ReduxFramework::$_url . 'inc/fields/button_set/field_button_set.min.js', 
                array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ),
                time(),
                true
            );

        }
    
    }
}