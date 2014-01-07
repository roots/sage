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
 * @subpackage  Field_Editor
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @author      Kevin Provance (kprovance)
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_editor' ) ) {

    /**
     * Main ReduxFramework_editor class
     *
     * @since       1.0.0
     */
    class ReduxFramework_editor extends ReduxFramework {
    
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

            // Setup up default editor_options
            $defaults = array(
                'textarea_name' => $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']', 
                'editor_class'  => $this->field['class'],
                'textarea_rows' => 10, //Wordpress default
                'teeny' => true,
            );

            if ( !isset( $this->field['editor_options'] ) || empty( $this->field['editor_options'] ) ) {
                $this->field['editor_options'] = array();
            }

            $this->field['editor_options'] = wp_parse_args( $this->field['editor_options'], $defaults );
            
            wp_editor( $this->value, $this->field['id'], $this->field['editor_options'] );
            
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
                'redux-field-editor-css', 
                ReduxFramework::$_url . 'inc/fields/editor/field_editor.css',
                time(),
                true
            );
        
        }

    }
}
