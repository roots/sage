<?php
class ReduxFramework_spacing extends ReduxFramework{	
	
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
			'units' 			=> '',
			'mode' 				=> '',
			'top'				=> true,
			'bottom'			=> true,
			'left'				=> true,
			'right'				=> true,
			'units_extended'	=> false,
			);
		$this->field = wp_parse_args( $this->field, $defaults );


		$defaults = array(
			'top'=>'',
			'right'=>'',
			'mode' => '',
			'bottom'=>'',
			'left'=>'',
			'mode'=>'padding',
			'units'=>'px',
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		if ( !empty( $this->field['units'] ) ) {
			$this->value['units'] = $this->field['units'];
		}

		if (  !in_array($this->value['units'], array( '%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px' ) ) ) {
			if ( !empty( $this->field['units'] ) && in_array($this->value['units'], array( '%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px' ) ) ) {
				$this->value['units'] = $this->field['units'];	
			}
		}

		if ( $this->field['mode'] !== "margin" && $this->field['mode'] !== "padding" ) {
			$mode = "";
		}
		
		if ( !empty( $this->field['mode'] ) ) {
			$this->field['mode'] = $this->field['mode']."-";
		}
		

			/**
			Top
			**/
			if ($this->field['top'] === true):
				if ( !empty($this->value['top'] ) &&  strpos( $this->value['top'], $this->value['units'] ) === false ) {
					$this->value['top'] = filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_INT);
					$this->value['top'] = $this->value['top'].$this->value['units'];
				}
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-up icon-large"></i></span><input type="text" class="redux-spacing-top redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Top','redux-framework').'" rel="'.$this->field['id'].'-top" value="'.filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_INT).'"><input type="hidden" placeholder="'.__('Top','redux-framework').'" id="'.$this->field['id'].'-top" name="'.$this->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'top]" value="'.$this->value['top'].'"></div>';
		  	endif;

			/**
			Right
			**/
			if ($this->field['right'] === true):
				if ( !empty($this->value['right'] ) &&  strpos( $this->value['right'], $this->value['units'] ) === false ) {
					$this->value['right'] = filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_INT);
					$this->value['right'] = $this->value['right'].$this->value['units'];
				}				
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-right icon-large"></i></span><input type="text" class="redux-spacing-right redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" rel="'.$this->field['id'].'-right" value="'.filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_INT).'"><input type="hidden" class="redux-spacing-right mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" id="'.$this->field['id'].'-right" name="'.$this->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'right]" value="'.$this->value['right'].'"></div>';
		  	endif;

			/**
			Bottom
			**/
			if ($this->field['bottom'] === true):
				if ( !empty($this->value['bottom'] ) &&  strpos( $this->value['bottom'], $this->value['units'] ) === false ) {
					$this->value['bottom'] = filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_INT);
					$this->value['bottom'] = $this->value['bottom'].$this->value['units'];
				}					
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-down icon-large"></i></span><input type="text" class="redux-spacing-bottom redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" rel="'.$this->field['id'].'-bottom" value="'.filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_INT).'"><input type="hidden" class="redux-spacing-bottom mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" id="'.$this->field['id'].'-bottom" name="'.$this->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'bottom]" value="'.$this->value['bottom'].'"></div>';
		  	endif;

			/**
			Left
			**/
			if ($this->field['left'] === true):
				if ( !empty($this->value['left'] ) &&  strpos( $this->value['left'], $this->value['units'] ) === false ) {
					$this->value['left'] = filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_INT);
					$this->value['left'] = $this->value['left'].$this->value['units'];
				}									
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-left icon-large"></i></span><input type="text" class="redux-spacing-left redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" rel="'.$this->field['id'].'-left" value="'.filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_INT).'"><input type="hidden" class="redux-spacing-left mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" id="'.$this->field['id'].'-left" name="'.$this->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'left]" value="'.$this->value['left'].'"></div>';
		  	endif;		


			/** 
			Units
			**/

			//if ( $this->field['units'] !== false ):

				echo '<div class="select_wrapper spacing-units" original-title="'.__('Units','redux-framework').'">';
				echo '<select data-placeholder="'.__('Units','redux-framework').'" class="redux-spacing redux-spacing-units select'.$this->field['class'].'" original-title="'.__('Units','redux-framework').'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][units]" id="'. $this->field['id'].'_units">';
				
				if ( $this->field['units_extended'] ) {
					$testUnits = array('px', 'em', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc');	
				} else {
					$testUnits = array('px', 'em', '%');
				}
				
				if ( in_array($this->field['units'], $testUnits) ) {
					echo '<option value="'.$this->field['units'].'" selected="selected">'.$this->field['units'].'</option>';
				} else {
					foreach($testUnits as $aUnit) {
						echo '<option value="'.$aUnit.'" '.selected($this->value['units'], $aUnit, false).'>'.$aUnit.'</option>';
					}

				}
				echo '</select></div>';

			//endif;





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
			'redux-field-spacing-js', 
			REDUX_URL.'inc/fields/spacing/field_spacing.min.js', 
			array('jquery', 'select2-js', 'jquery-numeric'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-spacing-css', 
			REDUX_URL.'inc/fields/spacing/field_spacing.css', 
			time(),
			true
		);	
			
		
	}//function

    public function output() {

    	if ( !empty( $this->field['mode'] ) && !in_array($this->field['mode'], array( 'padding', 'absolute', 'margin', '' ) ) ) {
    		unset( $this->field['mode'] );
    	}

    	if ( !isset( $this->field['mode'] ) ) {
    		$this->field['mode'] = "padding";
    	}

    	if ( $this->field['mode'] == "absolute" ) {
    		unset( $this->field['mode'] );
    	}


//absolute, padding, margin
        $keys = implode(", ", $this->output);
        $style = '<style type="text/css" class="redux-'.$this->field['type'].'">';
            $style .= $keys." {";
            foreach($this->value as $key=>$value) {
            	if ($key == "units") {
            		continue;
            	}
            	if ( !empty( $this->field['mode'] ) ) {
            		$style .= $this->field['mode'].'-';
            	}
                $style .= $key.': '.$value.'; ';
            }
            $style .= '}';
        $style .= '</style>';
        echo $style;
        
    }	
	
}//class