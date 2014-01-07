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
 * @subpackage  Field_Date
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_date' ) ) {

    /**
     * Main ReduxFramework_date class
     *
     * @since       1.0.0
     */
	class ReduxFramework_date extends ReduxFramework {
	
		/**
		 * Field Constructor.
		 *
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
		 * @since 		1.0.0
		 * @access		public
		 * @return		void
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
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
		 */
		public function render() {
    			$placeholder = (isset($this->field['placeholder'])) ? ' placeholder="' . esc_attr($this->field['placeholder']) . '" ' : '';
                                                
    			echo '<input data-id="'.$this->field['id'].'" type="text" id="'. $this->field['id'] .'-date" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']"' . $placeholder . 'value="' . $this->value . '" class="redux-datepicker ' . $this->field['class'] . '" />';
		}
	
		/**
	 	 * Enqueue Function.
		 *
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since 		1.0.0
		 * @access		public
		 * @return		void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'redux-field-date-js', 
				ReduxFramework::$_url . 'inc/fields/date/field_date.js',
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ),
				time(),
				true
			);
		}
	}
}
