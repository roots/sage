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
 * @subpackage  Field_Checkbox
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_checkbox' ) ) {

    /**
     * Main ReduxFramework_checkbox class
     *
     * @since       1.0.0
     */
    class ReduxFramework_checkbox extends ReduxFramework {   
    
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

	        if( !empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
				if (empty($this->field['args'])) {
					$this->field['args'] = array();
				}        	
	        	$this->field['options'] = $parent->get_wordpress_data($this->field['data'], $this->field['args']);
	        }
            $this->field['data_class'] = ( isset($this->field['multi_layout']) ) ? 'data-'.$this->field['multi_layout'] : 'data-full';
        
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

	            if( !empty( $this->field['options'] ) && ( is_array( $this->field['options'] ) || is_array( $this->field['default'] ) ) ) {
	                echo '<ul class="'.$this->field['data_class'].'">';
	            	if ( !isset( $this->value ) ) {
	            		$this->value = array();
	            	}
	            	if (!is_array($this->value)) {
	            		$this->value = array();
	            	}

	                foreach( $this->field['options'] as $k => $v ) {
	                	
	                    if (empty($this->value[$k])) {
	                    	$this->value[$k] = "";
	                    }
	                    	
	                    echo '<li>';
	                    echo '<label for="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']', array('[' => '_', ']' => '')) . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '">';
	                    echo '<input type="checkbox" class="checkbox ' . $this->field['class'] . '" id="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']', array('[' => '_', ']' => '')) . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" value="1" ' . checked( $this->value[$k], '1', false ) . '/>';
	                    echo ' ' . $v . '</label>';
	                    echo '</li>';
	                
	                }

	                echo '</ul>';   

	            } else {

	                echo ( $this->field['desc'] != '' ) ? ' <label for="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . ']', array('[' => '_', ']' => '')) . '">' : '';
	        
	                echo '<input type="checkbox" id="' . strtr($this->args['opt_name'] . '[' . $this->field['id'] . ']', array('[' => '_', ']' => '')) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="1" class="checkbox ' . $this->field['class'] . '" ' . checked( $this->value, '1', false ) . '/>';
	        
	            }

        }
    }
}