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
 * @subpackage  Field_Border
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_border' ) ) {

    /**
     * Main ReduxFramework_border class
     *
     * @since       1.0.0
     */
    class ReduxFramework_border extends ReduxFramework{ 
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @param       array $field
         * @param       array $value
         * @param       array $parent
         * @return      void
         */
        public function __construct( $field = array(), $value = '', $parent ) {
        
            parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );
        
            $this->field = $field;
            $this->value = $value;      
        }

    
        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since ReduxFramework 1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            // No errors please
            $defaults = array(
                'border-color' => '',
                'border-style' => '',
                'border-width'  => '',
                'units'         => 'px'
                );
            $this->field = wp_parse_args( $this->field, $defaults );
            $this->value = wp_parse_args( $this->value, $defaults );

            if ( empty( $this->value['units'] ) || ( !in_array($this->value['units'], array( '%, in, cm, mm, em, ex, pt, pc, px' ) ) ) ) {
                if ( empty( $this->field['units'] ) || ( !in_array($this->field['units'], array( '%, in, cm, mm, em, ex, pt, pc, px' ) ) ) ) {
                    $this->field['units'] = "px";
                }
                $this->value['units'] = $this->field['units'];
            }            

            if( empty( $this->field['min'] ) )
                $this->field['min'] = 0;
        
            if( empty( $this->field['max'] ) )
                $this->field['max'] = 10;
       
            

            $options = array(
                ''          => 'None',
                'solid'     => 'Solid',
                'dashed'    => 'Dashed',
                'dotted'    => 'Dotted'
            );

            echo '<div class="redux-border">';
        
	            echo '<select original-title="' . __( 'Border size', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-width]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][border-width]" class="tips redux-border-size mini' . $this->field['class'] . '" rows="6" data-id="'.$this->field['id'].'">';

	            for( $k = $this->field['min']; $k <= $this->field['max']; $k++ ) {
	                echo '<option value="' . $k.$this->field['units'] . '"' . selected( $this->value['border-width'], $k.$this->field['units'], false) . '>' . $k . '</option>';
	            }

	            echo '</select>';

	            echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-style]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][border-style]" class="tips redux-border-style' . $this->field['class'] . '" rows="6" data-id="'.$this->field['id'].'">';

	            foreach( $options as $k => $v ) {
	                echo '<option value="' . $k . '"' . selected( $this->value['border-style'], $k, false ) . '>' . $v . '</option>';
	            }

	            echo '</select>';   

	            echo '<input name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][border-color]" id="' . $this->field['id'] . '-border" class="redux-border-color redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $this->value['border-color'] . '"  data-default-color="' . $this->field['border-color'] . '" data-id="'.$this->field['id'].'" />';
	            
	        
            
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
        public function enqueue(){
        
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
        
            wp_enqueue_style(
                'redux-field-border-css', 
                ReduxFramework::$_url . 'inc/fields/border/field_border.css', 
                time(),
                true
            );

        }

        public function output() {
            if ( !isset($this->field['output']) || empty( $this->field['output'] ) ) {
                return;
            }

            $keys = implode(",", $this->field['output']);

            $style = '<style type="text/css" class="redux-'.$this->field['type'].'">';
                $style .= $keys."{";
                foreach($this->value as $key=>$value) {
                    if (empty($value)) {
                        $value = 0;
                    }
                    $style .= $key.':'.$value.';';
                }
                $style .= '}';
            $style .= '</style>';
            echo $style;
            
        }

    }
}