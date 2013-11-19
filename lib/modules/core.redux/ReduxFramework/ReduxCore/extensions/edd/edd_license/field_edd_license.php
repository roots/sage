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
if( !class_exists( 'ReduxFramework_edd_license' ) ) {

    /**
     * Main ReduxFramework_color class
     *
     * @since       1.0.0
     */
	class ReduxFramework_edd_license extends ReduxFramework {
	
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
		
			parent::__construct( $parent->sections, $parent->args );

			$this->field = $field;
			$this->value = $value;

			// Create defaults array
			$defaults = array(
				'mode' => '',
				'path' => '',
				'remote_api_url' => '',
				'version' => '',
				'item_name' => '',
				'author' => '',
				'mode' => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );    

			$defaults = array(
				'license' 	=> '',
				'status' 	=> '',
			);

			$this->value = wp_parse_args( $this->value, $defaults );			

			$this->parent = $parent;		
		
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
			echo '</td></tr></table><div id="' . $this->field['id'] . '-notice" class="redux-warning  redux-info-field">';
			echo '<p class="redux-info-icon"><i class="el-icon-info-sign icon-large"></i></p><h1 class="redux-info-desc"><b>' . __('License Status', 'redux-framework') . ': <span id="' . $this->field['id'] . '-status_notice">'.ucfirst($this->value['status']).'</span></b></h1></div>';
			echo '<div style="text-align: center;">';
			echo '<input type="hidden" class="redux-edd " type="text" id="' . $this->field['id'] . '-field_id" value="' . $this->field['id'] . '" " />'; 
			echo '<input type="hidden" class="redux-edd " type="text" id="' . $this->field['id'] . '-remote_api_url" value="' . $this->field['remote_api_url'] . '" " />'; 
			echo '<input type="hidden" class="redux-edd " type="text" id="' . $this->field['id'] . '-version" value="' . $this->field['version'] . '" " />'; 
			echo '<input type="hidden" class="redux-edd " type="text" id="' . $this->field['id'] . '-item_name" value="' . $this->field['item_name'] . '" " />'; 
			echo '<input type="hidden" class="redux-edd " type="text" id="' . $this->field['id'] . '-author" value="' . $this->field['author'] . '" " />'; 
			echo '<input name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][license]"  id="' . $this->field['id'] . '-license" class="redux-edd ' . $this->field['class'] . '"  type="text" value="' . $this->value['license'] . '" " />'; 
			echo '<input type="hidden" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][status]" id="' . $this->field['id'] . '-status" class="redux-edd ' . $this->field['class'] . '" type="text" value="' . $this->value['status'] . '" " />'; 
			echo '&nbsp; <a href="#" data-id="'.$this->field['id'].'" class="button button-primary redux-EDDAction" data-edd_action="check_license">Verify License</a>';
			echo '&nbsp; <a href="#" data-id="'.$this->field['id'].'" class="button button-primary redux-EDDAction" data-edd_action="activate_license">Activate License</a>';
			echo '&nbsp; <a href="#" data-id="'.$this->field['id'].'" class="button redux-EDDAction" data-edd_action="deactivate_license">Deactivate License</a>';
			if (isset($this->parent->args['edd'])) {
				foreach( $this->parent->args['edd'] as $k => $v ) {
					echo '<input type="hidden" data-id="'.$this->field['id'].'" id="' . $this->field['id'] . '-'.$k.'" class="redux-edd edd-'.$k.'"  type="text" value="' . $v . '" " />';
				}
			}
			echo '</div>';
			echo '<table class="form-table no-border" style="margin-top: 0;"><tbody><tr><th></th><td>';
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

			wp_enqueue_script(
				'redux-field-edd-js', 
				ReduxFramework::$_url . 'extensions/edd/edd_license/field_edd_license.js', 
				array( 'jquery' ),
				time(),
				true
			);

			wp_enqueue_style(
				'redux-field-edd-css', 
				ReduxFramework::$_url . 'extensions/edd/edd_license/field_edd_license.css', 
				time(),
				true
			);
		
		}
	}
}