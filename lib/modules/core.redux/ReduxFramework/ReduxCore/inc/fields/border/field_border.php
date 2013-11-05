<?php
class ReduxFramework_border extends ReduxFramework{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
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
			'all'				=> false,
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
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		$value = array(
			'top' => isset( $this->value['border-top'] ) ? filter_var($this->value['border-top'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_INT),
			'right' => isset( $this->value['border-right'] ) ? filter_var($this->value['border-right'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_INT),
			'bottom' => isset( $this->value['border-bottom'] ) ? filter_var($this->value['border-bottom'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_INT),
			'left' => isset( $this->value['border-left'] ) ? filter_var($this->value['border-left'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_INT),
            'color' => isset( $this->value['border-color'] ) ? $this->value['border-color'] : $this->value['color'],
            'style' => isset( $this->value['border-style'] ) ? $this->value['border-style'] : $this->value['style']
		);

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
			echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="icon-fullscreen icon-large"></i></span><input type="text" class="redux-border-all redux-border-input mini'.$this->field['class'].'" placeholder="'.__('All','redux-framework').'" rel="'.$this->field['id'].'-all" value="'.$this->value['top'].'"></div>';
		}

		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-top" name="'.$this->args['opt_name'].'['.$this->field['id'].'][border-top]" value="' . ( $this->value['top'] ? $this->value['top'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-right" name="'.$this->args['opt_name'].'['.$this->field['id'].'][border-right]" value="' . ( $this->value['right'] ? $this->value['right'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-bottom" name="'.$this->args['opt_name'].'['.$this->field['id'].'][border-bottom]" value="' . ( $this->value['bottom'] ? $this->value['bottom'] . 'px' : '' ) . '">';
		echo '<input type="hidden" class="redux-border-value" id="'.$this->field['id'].'-left" name="'.$this->args['opt_name'].'['.$this->field['id'].'][border-left]" value="' . ( $this->value['left'] ? $this->value['left'] . 'px' : '' ) . '">';

		if ( !isset( $this->field['all'] ) || $this->field['all'] !== true ) :
			/**
			Top
			**/
			if ($this->field['top'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="icon-arrow-up icon-large"></i></span><input type="text" class="redux-border-top redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Top','redux-framework').'" rel="'.$this->field['id'].'-top" value="'.$this->value['top'].'"></div>';
		  	endif;

			/**
			Right
			**/
			if ($this->field['right'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="icon-arrow-right icon-large"></i></span><input type="text" class="redux-border-right redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" rel="'.$this->field['id'].'-right" value="'.$this->value['right'].'"></div>';
		  	endif;

			/**
			Bottom
			**/
			if ($this->field['bottom'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="icon-arrow-down icon-large"></i></span><input type="text" class="redux-border-bottom redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" rel="'.$this->field['id'].'-bottom" value="'.$this->value['bottom'].'"></div>';
		  	endif;

			/**
			Left
			**/
			if ($this->field['left'] === true):
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="icon-arrow-left icon-large"></i></span><input type="text" class="redux-border-left redux-border-input mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" rel="'.$this->field['id'].'-left" value="'.$this->value['left'].'"></div>';
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
                echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-style]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][border-style]" class="tips redux-border-style' . $this->field['class'] . '" rows="6" data-id="'.$this->field['id'].'">';
                    foreach( $options as $k => $v ) {
                        echo '<option value="' . $k . '"' . selected( $value['style'], $k, false ) . '>' . $v . '</option>';
                    }
                echo '</select>';  

            endif;

            /** 
            Color
            **/

            if ( $this->field['color'] != false ):
                echo '<input name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][border-color]" id="' . $this->field['id'] . '-border" class="redux-border-color redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $value['color'] . '"  data-default-color="' . $this->field['border-color'] . '" data-id="'.$this->field['id'].'" />';
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
			ReduxFramework::$_url.'inc/fields/border/field_border.min.js', 
			array('jquery', 'select2-js', 'jquery-numeric'),
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

        if ( !isset($this->field['output']) || empty( $this->field['output'] ) ) {
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

        print_r($value);
    	
		//absolute, padding, margin
        $keys = implode(",", $this->field['output']);
        $style = '<style type="text/css" class="redux-'.$this->field['type'].'">';
            $style .= $keys."{";
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
            
            $style .= '}';
        $style .= '</style>';
        echo $style;
        
    }	
	
}//class
