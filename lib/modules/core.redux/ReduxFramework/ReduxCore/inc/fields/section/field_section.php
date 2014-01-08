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
 * @subpackage  Field_Section
 * @author      Tobias Karnetze (athoss.de)
 * @version     1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_divide' ) ) {

    /**
     * Main ReduxFramework_heading class
     *
     * @since       1.0.0
     */
	class ReduxFramework_section extends ReduxFramework {
	
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
                    
	        // No errors please
	        $defaults = array(
	            'indent' => '',
	            'style' => '',
	            'class' => '',
	            'title' => '',
	            'subtitle' => '',
	        );
	        $this->field = wp_parse_args( $this->field, $defaults );

	        $add_class = '';
	        if (isset($this->field['indent']) && !empty($this->field['indent'])){                                                
	            $add_class = ' form-table-section-indented';
	        }
	        
	        echo '</td></tr></table><div id="' . $this->field['id'] . '" class="' . $this->field['style'] . $this->field['class'] . '">';
	        if (!empty($this->field['title'])){
	            echo '<h3>'.$this->field['title'].'</h3>';
	        }
	        if (!empty($this->field['subtitle'])){                    
	        	echo '<div class="redux-section-desc">'.$this->field['subtitle'].'</div>';
	        }
	        echo '</div><table class="form-table form-table-section no-border'.$add_class.'"><tbody><tr><th></th><td>';  
            
		}
	}	
}