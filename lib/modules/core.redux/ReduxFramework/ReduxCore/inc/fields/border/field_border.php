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
 * @subpackage  Border
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( class_exists( 'ReduxFramework_border' ) ) return;

class ReduxFramework_border extends ReduxFramework {	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		//parent::__construct( $parent->sections, $parent->args );
		$this->parent = $parent;
		$this->field = $field;
		$this->value = $value;
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function render(){
		
		// No errors please
		$defaults = array(
			'top'				=> true,
			'bottom'			=> true,
			'all'				=> true,
            'style'             => true,
            'color'             => true,
			'left'				=> true,
			'right'				=> true,
			);
		$this->field = wp_parse_args( $this->field, $defaults );

		$defaults = array(
			'top'=>'',
			'right'=>'',
			'bottom'=>'',
			'left'=>'',
			'color'=>'',
			'style'=>'',
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		$value = array(
			'top' => isset( $this->value['border-top'] ) ? filter_var($this->value['border-top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'right' => isset( $this->value['border-right'] ) ? filter_var($this->value['border-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'bottom' => isset( $this->value['border-bottom'] ) ? filter_var($this->value['border-bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'left' => isset( $this->value['border-left'] ) ? filter_var($this->value['border-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'color' => isset( $this->value['border-color'] ) ? $this->value['border-color'] : $this->value['color'],
            'style' => isset( $this->value['border-style'] ) ? $this->value['border-style'] : $this->value['style']
		);

		if ( ( isset( $this->value['width'] ) || isset( $this->value['border-width'] ) ) ) {
			if ( isset( $this->value['border-width'] ) && !empty( $this->value['border-width'] ) ) {
				$this->value['width'] = $this->value['border-width'];
			}
			$value['top'] = $this->value['width'];
			$value['right'] = $this->value['width'];
			$value['bottom'] = $this->value['width'];
			$value['left'] = $this->value['width'];
		}

		$this->value = $value;

		$defaults = array(
			'top'=>'',
			'right'=>'',
			'bottom'=>'',
			'left'=>'',
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		echo '<input type="hidden" class="field-units" value="px">';

		if ( isset( $this->field['all'] ) && $this->field['all'] == true ) {
			echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el-icon-fullscreen icon-large"></i></span><input type="text" class="redux-border-all redux-border-input mini'.$this->field['class'].'" placeholder="'.__('All','redux-framework').'" rel="'.$this->field['id'].'-all" value="'.$this->value['top'].'"></div>';
		}

		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-top" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].'][border-top]" value="' . ( $this->value['top'] ? $this->value['top'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-right" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].'][border-right]" value="' . ( $this->value['right'] ? $this->value['right'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-bottom" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].'][border-bottom]" value="' . ( $this->value['bottom'] ? $this->value['bottom'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-left" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].'][border-left]" value="' . ( $this->value['left'] ? $this->value['left'] . 'px' : '' ) . '">';

		if ( !isset( $this->field['all'] ) || $this->field['all'] !== true ) :
			/**
			Top
			**/
			if ($this->field['top'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-up icon-large"></i></span><input type="text" class="redux-border-top redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Top','redux-framework').'" rel="'.$this->field['id'].'-top" value="'.$this->value['top'].'"></div>';
		  	endif;

			/**
			Right
			**/
			if ($this->field['right'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-right icon-large"></i></span><input type="text" class="redux-border-right redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" rel="'.$this->field['id'].'-right" value="'.$this->value['right'].'"></div>';
		  	endif;

			/**
			Bottom
			**/
			if ($this->field['bottom'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-down icon-large"></i></span><input type="text" class="redux-border-bottom redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" rel="'.$this->field['id'].'-bottom" value="'.$this->value['bottom'].'"></div>';
		  	endif;

			/**
			Left
			**/
			if ($this->field['left'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-left icon-large"></i></span><input type="text" class="redux-border-left redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" rel="'.$this->field['id'].'-left" value="'.$this->value['left'].'"></div>';
		  	endif;		

		endif;

            /** 
            Border-style
            **/

            if ( $this->field['style'] != false ):
                $options = array(
                    'solid'     => 'Solid',
                    'dashed'    => 'Dashed',
                    'dotted'    => 'Dotted',
                    'none'      => 'None'
                );
                echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-style]" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][border-style]" class="tips redux-border-style' . $this->field['class'] . '" rows="6" data-id="'.$this->field['id'].'">';
                    foreach( $options as $k => $v ) {
                        echo '<option value="' . $k . '"' . selected( $value['style'], $k, false ) . '>' . $v . '</option>';
                    }
                echo '</select>';  

            endif;

            /** 
            Color
            **/

            if ( $this->field['color'] != false ):
            	$default = isset( $this->field['border-color'] ) ? $this->field['border-color'] : "";
            	$default = ( empty( $default ) && isset( $this->field['color'] ) ) ? $this->field['color'] : "";
                echo '<input name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][border-color]" id="' . $this->field['id'] . '-border" class="redux-border-color redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $value['color'] . '"  data-default-color="' . $default . '" data-id="'.$this->field['id'].'" />';
            endif;


	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function enqueue(){
		wp_enqueue_script( 'select2-js' );
		wp_enqueue_style( 'select2-css' );	

		wp_enqueue_script(
			'redux-field-border-js', 
			ReduxFramework::$_url.'inc/fields/border/field_border.js', 
			array('jquery', 'select2-js', 'redux-vendor'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-border-css', 
			ReduxFramework::$_url.'inc/fields/border/field_border.css', 
			time(),
			true
		);	
			
		
	}//function

    public function output() {

		if ( ( !isset( $this->field['output'] ) || !is_array( $this->field['output'] ) ) && ( !isset( $this->field['compiler'] ) || !is_array( $this->field['compiler'] ) ) ) {
			return;
		}

        $cleanValue = array(
            'top' => !empty( $this->value['border-top'] ) ? $this->value['border-top'] : 'inherit',
            'right' => !empty( $this->value['border-right'] ) ? $this->value['border-right'] : 'inherit',
            'bottom' => !empty( $this->value['border-bottom'] ) ? $this->value['border-bottom'] : 'inherit',
            'left' => !empty( $this->value['border-left'] ) ? $this->value['border-left'] : 'inherit',
            'color' => !empty( $this->value['border-color'] ) ? $this->value['border-color'] : 'inherit',
            'style' => !empty( $this->value['border-style'] ) ? $this->value['border-style'] : 'inherit'
        );

        $style = "";
    	
		//absolute, padding, margin
        if ( !isset( $this->field['all'] ) || $this->field['all'] != true ) {
			foreach($cleanValue as $key=>$value) {
            	if ($key == "color" || $key == "style" ) {
            		continue;
            	}
                $style .= 'border-' . $key . ':' . $value . ' '.$cleanValue['style'] . ' '. $cleanValue['color'] . ';';
            }            	
        } else {
        	$style .= 'border:' . $value['top'] . ' ' . $cleanValue['style'] . ' '. $cleanValue['color'] .';';
        }

		if ( !empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
			$keys = implode(",", $this->field['output']);
			$this->parent->outputCSS .= $keys . "{" . $style . '}';
		}

		if ( !empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
			$keys = implode(",", $this->field['compiler']);
			$style = $keys . "{" . $style . '}';
			$this->parent->compilerCSS .= $keys . "{" . $style . '}';
		}		
        
    }	
	
}//class
