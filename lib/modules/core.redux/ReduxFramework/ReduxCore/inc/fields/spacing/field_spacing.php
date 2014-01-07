<?php
class ReduxFramework_spacing extends ReduxFramework{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
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
	 * @since ReduxFramework 1.0.0
	*/
	function render(){
	
		if ( isset( $this->field['units'] ) && !in_array( $this->field['units'], array( '', false, '%', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc', 'px' ) ) ) {
			unset( $this->field['units'] );
		}	

		if ( isset( $this->value['units'] ) && !in_array( $this->value['units'], array( '', '%', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc', 'px' ) ) ) {
			unset( $this->value['units'] );
		}
	
		// No errors please
		$defaults = array(
			'units' 			=> '',
			'mode' 				=> 'padding',
			'top'				=> true,
			'bottom'			=> true,
			'all'				=> false,
			'left'				=> true,
			'right'				=> true,
			'units_extended'	=> false,
			'display_units' 	=> true				
			);
		$this->field = wp_parse_args( $this->field, $defaults );


		if ( $this->field['mode'] == "absolute" ) {
			$this->field['units'] = "";
			$this->value['units'] = "";
		}

		if ( $this->field['units'] == false ) {
			$this->value == "";
		}

		$defaults = array(
			'top'=>'',
			'right'=>'',
			'bottom'=>'',
			'left'=>'',
			'units'=>'px'		
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		if ( isset( $this->field['mode'] ) && !in_array( $this->field['mode'], array( 'margin', 'padding' ) ) ) {
			if ( $this->field['mode'] == "absolute" ) {
				$absolute = true;
			} 
			$this->field['mode'] = "";	
		}

		$value = array(
			'top' => isset( $this->value[$this->field['mode'].'-top'] ) ? filter_var($this->value[$this->field['mode'].'-top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'right' => isset( $this->value[$this->field['mode'].'-right'] ) ? filter_var($this->value[$this->field['mode'].'-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'bottom' => isset( $this->value[$this->field['mode'].'-bottom'] ) ? filter_var($this->value[$this->field['mode'].'-bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
			'left' => isset( $this->value[$this->field['mode'].'-left'] ) ? filter_var($this->value[$this->field['mode'].'-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
		);
		


		if ( isset( $this->field['units'] ) && $this->field['units'] != "" ) {
			$this->value['units'] = $this->field['units'];
		}

		if ( isset( $this->field['units'] ) && !isset( $this->value['units'] ) ) { // Value should equal field units
			$this->value['units'] = $this->field['units'];
		} else if ( !isset( $this->field['units'] ) && !isset( $this->value['units'] ) && $this->field['units'] !== false && $this->field['units'] !== "" ) { // If both undefined
			$this->field['units'] = '';
			$this->value['units'] = '';
		} else if ( !isset( $this->field['units'] ) && isset( $this->value['units'] ) ) { // If Value is defined
			$this->field['units'] = $this->value['units'];	// Make the field have it
		}
		if ( isset( $this->field['units'] ) ) {
			$value['units'] = $this->value['units'];	
		}

		$this->value = $value;
		
		if ( !empty( $this->field['mode'] ) ) {
			$this->field['mode'] = $this->field['mode']."-";
		}


		$defaults = array(
			'top'=>'',
			'right'=>'',
			'bottom'=>'',
			'left'=>'',
			'units'=>''
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		echo '<input type="hidden" class="field-units" value="'.$this->field['units'].'">';

		if ( isset( $this->field['all'] ) && $this->field['all'] == true ) {
			echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el-icon-fullscreen icon-large"></i></span><input type="text" class="redux-spacing-all redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('All','redux-framework').'" rel="'.$this->field['id'].'-all" value="'.$this->value['top'].'"></div>';
		}

		if ($this->field['top'] === true):
			echo '<input type="hidden" class="redux-spacing-value" id="'.$this->field['id'].'-top" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'top]" value="'.$this->value['top'].(!empty($this->value['top']) ? $this->value['units'] : '').'">';
		endif;

		if ($this->field['right'] === true):
			echo '<input type="hidden" class="redux-spacing-value" id="'.$this->field['id'].'-right" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'right]" value="'.$this->value['right'].(!empty($this->value['right']) ? $this->value['units'] : '').'">';
		endif;

		if ($this->field['bottom'] === true):
			echo '<input type="hidden" class="redux-spacing-value" id="'.$this->field['id'].'-bottom" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'bottom]" value="'.$this->value['bottom'].(!empty($this->value['bottom']) ? $this->value['units'] : '').'">';
		endif;

		if ($this->field['left'] === true):
			echo '<input type="hidden" class="redux-spacing-value" id="'.$this->field['id'].'-left" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].']['.$this->field['mode'].'left]" value="'.$this->value['left'].(!empty($this->value['left']) ? $this->value['units'] : '').'">';
		endif;

		if ( !isset( $this->field['all'] ) || $this->field['all'] !== true ) :
			/**
			Top
			**/
			if ($this->field['top'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-up icon-large"></i></span><input type="text" class="redux-spacing-top redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Top','redux-framework').'" rel="'.$this->field['id'].'-top" value="'.$this->value['top'].'"></div>';
		  	endif;

			/**
			Right
			**/
			if ($this->field['right'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-right icon-large"></i></span><input type="text" class="redux-spacing-right redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" rel="'.$this->field['id'].'-right" value="'.$this->value['right'].'"></div>';
		  	endif;

			/**
			Bottom
			**/
			if ($this->field['bottom'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-down icon-large"></i></span><input type="text" class="redux-spacing-bottom redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" rel="'.$this->field['id'].'-bottom" value="'.$this->value['bottom'].'"></div>';
		  	endif;

			/**
			Left
			**/
			if ($this->field['left'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-left icon-large"></i></span><input type="text" class="redux-spacing-left redux-spacing-input mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" rel="'.$this->field['id'].'-left" value="'.$this->value['left'].'"></div>';
		  	endif;		

		endif;

			/** 
			Units
			**/

			if ( $this->field['units'] !== false && !isset( $absolute ) && $this->field['display_units'] == true ):

				echo '<div class="select_wrapper spacing-units" original-title="'.__('Units','redux-framework').'">';
				echo '<select data-placeholder="'.__('Units','redux-framework').'" class="redux-spacing redux-spacing-units select'.$this->field['class'].'" original-title="'.__('Units','redux-framework').'" name="'.$this->parent->args['opt_name'].'['.$this->field['id'].'][units]" id="'. $this->field['id'].'_units">';

				if ( $this->field['units_extended'] ) {
					$testUnits = array('px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc');	
				} else {
					$testUnits = array('px', 'em', 'rem', '%');
				}
				if ( $this->field['units'] != "" ) {
					$testUnits = array( $this->field['units'] );
				}

				echo '<option></option>';
				
				if ( in_array($this->field['units'], $testUnits) ) {
					echo '<option value="'.$this->field['units'].'" selected="selected">'.$this->field['units'].'</option>';
				} else {
					foreach($testUnits as $aUnit) {
						echo '<option value="'.$aUnit.'" '.selected($this->value['units'], $aUnit, false).'>'.$aUnit.'</option>';
					}
				}
				echo '</select></div>';

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

		wp_enqueue_script(
			'redux-field-spacing-js', 
			ReduxFramework::$_url.'inc/fields/spacing/field_spacing.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-spacing-css', 
			ReduxFramework::$_url.'inc/fields/spacing/field_spacing.css', 
			time(),
			true
		);	
			
		
	}//function

    public function output() {

        if ( ( !isset( $this->field['output'] ) || !is_array( $this->field['output'] ) ) && !isset( $this->field['compiler'] ) || !is_array( $this->field['compiler'] ) ) {
            return;
        }  

        if ( !isset( $this->field['mode'] ) ) {
        	$this->field['mode'] = "padding";
        }

    	if ( isset( $this->field['mode'] ) && !in_array( $this->field['mode'], array( 'padding', 'absolute', 'margin') ) ) {
    		$this->field['mode'] = "";
    	}

    	$mode = ( $this->field['mode'] != "absolute" ) ? $this->field['mode'] : "";
    	$units = isset( $this->value['units'] ) ? $this->value['units'] : "";
    	
		//absolute, padding, margin
        $keys = implode(",", $this->field['output']);
        $style = '';

        if ( !empty( $mode ) ) {
			foreach($this->value as $key=>$value) {
            	if ($key == "units") {
            		continue;
            	}
            	if (empty($value)) {
            		$value = 0;
            	}
                $style .= $key.':'.$value.';';
            }            	
        } else {
			$cleanValue = array(
				'top' => isset( $this->value[$mode.'-top'] ) ? filter_var($this->value[$mode.'-top'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['top'], FILTER_SANITIZE_NUMBER_INT),
				'right' => isset( $this->value[$mode.'-right'] ) ? filter_var($this->value[$mode.'-right'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['right'], FILTER_SANITIZE_NUMBER_INT),
				'bottom' => isset( $this->value[$mode.'-bottom'] ) ? filter_var($this->value[$mode.'-bottom'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['bottom'], FILTER_SANITIZE_NUMBER_INT),
				'left' => isset( $this->value[$mode.'-left'] ) ? filter_var($this->value[$mode.'-left'], FILTER_SANITIZE_NUMBER_INT) : filter_var($this->value['left'], FILTER_SANITIZE_NUMBER_INT)
			);	            	
        	$style .= $mode.':'.$cleanValue['top'].$units.';';
        }
            
        if ( !empty($style ) ) {
            
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
        
    }	
	
}//class