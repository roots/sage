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
 * @package     Redux_Field
 * @subpackage  Button_Set
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
         * Holds configuration settings for each field in a model.
         * Defining the field options
         *
         * array['fields']              array Defines the fields to be shown by scaffolding.
         *          [fieldName]         array Defines the options for a field, or just enables the field if array is not applied.
         *              ['name']        string Overrides the field name (default is the array key)
         *              ['model']       string (optional) Overrides the model if the field is a belongsTo associated value.
         *              ['width']       string Defines the width of the field for paginate views. Examples are "100px" or "auto"
         *              ['align']       string Alignment types for paginate views (left, right, center)
         *              ['format']      string Formatting options for paginate fields. Options include ('currency','nice','niceShort','timeAgoInWords' or a valid Date() format)
         *              ['title']       string Changes the field name shown in views.
         *              ['desc']        string The description shown in edit/create views.
         *              ['readonly']    boolean True prevents users from changing the value in edit/create forms.
         *              ['type']        string Defines the input type used by the Form helper (example 'password')
         *              ['options']     array Defines a list of string options for drop down lists.
         *              ['editor']      boolean If set to True will show a WYSIWYG editor for this field.
         *              ['default']     string The default value for create forms.
         *
         * @param array $arr (See above)
         * @return Object A new editor object.
         **/

        static $_properties = array(
                'id'=> 'Identifier',

            );

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
        
            echo '<div class="buttonset ui-buttonset">';
            
            foreach( $this->field['options'] as $k => $v ) {
                
                echo '<input data-id="'.$this->field['id'].'" type="radio" id="'.$this->field['id'].'-buttonset'.$k.'" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']" class="' . $this->field['class'] . '" value="' . $k . '" ' . checked( $this->value, $k, false ) . '/>';
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
                ReduxFramework::$_url.'inc/fields/button_set/field_button_set.js', 
                array(),
                time(),
                true
            );            

        }
    
    }
}