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
 * @subpackage  Field_Color
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_color' ) ) {

    /**
     * Main ReduxFramework_color class
     *
     * @since       1.0.0
     */
	class ReduxFramework_color extends ReduxFramework {
	
		/**
		 * Field Constructor.
		 *
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
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
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
		 */
		public function render() {

			echo '<input data-id="'.$this->field['id'].'" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" id="' . $this->field['id'] . '-color" class="redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $this->value . '"  data-default-color="' . $this->field['default'] . '" />';

			if ( !isset( $this->field['transparent'] ) || $this->field['transparent'] !== false ) {
				$tChecked = "";
				if ( $this->value == "transparent" ) {
					$tChecked = ' checked="checked"';
				}
				echo '<label for="' . $this->field['id'] . '-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . $this->field['class'] . '" id="' . $this->field['id'] . '-transparency" data-id="'.$this->field['id'] . '-color" value="1"'.$tChecked.'> '.__('Transparent', 'redux-framework').'</label>';				
			}

		}
	
		/**
		 * Enqueue Function.
		 *
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since		1.0.0
		 * @access		public
		 * @return		void
		 */
		public function enqueue() {

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'redux-field-color-js', 
				ReduxFramework::$_url . 'inc/fields/color/field_color.min.js', 
				array( 'jquery', 'wp-color-picker' ),
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