<?php
class ReduxFramework_dimensions extends ReduxFramework{	
	
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
			'width'				=> true,
			'height'			=> true,
			'units_extended'	=> false,
			);

		$this->field['units'] = "em";

		$this->field = wp_parse_args( $this->field, $defaults );

		if ( isset( $this->field['units'] ) && !in_array($this->field['units'], array( '', '%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px' ) ) ) {
			unset( $this->field['units'] );
		}	

		if ( isset( $this->value['units'] ) && !in_array($this->value['units'], array( '', '%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px' ) ) ) {
			unset( $this->value['units'] );
		}

		if ( isset( $this->field['units'] ) && !isset( $this->value['units'] ) ) { // Value should equal field units
			$this->value['units'] = $this->field['units'];
		} else if ( !isset( $this->field['units'] ) && !isset( $this->value['units'] ) ) { // If both undefined
			$this->field['units'] = 'px';
			$this->value['units'] = 'px';
		} else if ( !isset( $this->field['units'] ) && isset( $this->value['units'] ) ) { // If Value is defined
			if ( empty( $this->value['units'] ) ) { // Value can't be empty in this case for this field
				$this->field['units'] = 'px';
				$this->value['units'] = 'px';
			} else {
				$this->field['units'] = $this->value['units'];	// Make the field have it
			}
		}

		$defaults = array(
			'width'=>'',
			'height'=>'',
		);

		$this->value = wp_parse_args( $this->value, $defaults );

	  	echo '<fieldset id="'.$this->field['id'].'" class="redux-dimensions-container" data-id="'.$this->field['id'].'">';
	  		echo '<input type="hidden" id="field-units" value="'.$this->field['units'].'"></div>';
			/**
			Width
			**/
			if ($this->field['width'] === true):
				if ( !empty($this->value['width'] ) &&  strpos( $this->value['width'], $this->value['units'] ) === false ) {
					$this->value['width'] = filter_var($this->value['width'], FILTER_SANITIZE_NUMBER_INT);
					if ($this->field['units'] !== false ) {
						$this->value['width'] .= $this->value['units'];	
					}
				}				
				echo '<div class="field-dimensions-input input-prepend">';
				echo '<span class="add-on"><i class="icon-resize-horizontal icon-large"></i></span>';
				echo '<input type="text" class="redux-dimensions-input redux-dimensions-width mini'.$this->field['class'].'" placeholder="'.__('Width','redux-framework').'" rel="'.$this->field['id'].'-width" value="'.filter_var($this->value['width'], FILTER_SANITIZE_NUMBER_INT).'">';
				echo '<input data-id="'.$this->field['id'].'" type="hidden" id="'.$this->field['id'].'-width" name="'.$this->args['opt_name'].'['.$this->field['id'].'][width]" value="'.$this->value['width'].'"></div>';
		  	endif;

			/**
			Height
			**/
			if ($this->field['height'] === true):
				if ( !empty($this->value['height'] ) &&  strpos( $this->value['height'], $this->value['units'] ) === false ) {
					$this->value['height'] = filter_var($this->value['height'], FILTER_SANITIZE_NUMBER_INT);
					if ($this->field['units'] !== false ) {
						$this->value['height'] .= $this->value['units'];	
					}
				}					
				echo '<div class="field-dimensions-input input-prepend">';
				echo '<span class="add-on"><i class="icon-resize-vertical icon-large"></i></span>';
				echo '<input type="text" class="redux-dimensions-input redux-dimensions-height mini'.$this->field['class'].'" placeholder="'.__('height','redux-framework').'" rel="'.$this->field['id'].'-height" value="'.filter_var($this->value['height'], FILTER_SANITIZE_NUMBER_INT).'">';
				echo '<input data-id="'.$this->field['id'].'" type="hidden" id="'.$this->field['id'].'-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'.$this->value['height'].'"></div>';
		  	endif;

			/** 
			Units
			**/

			if ( $this->field['units'] !== false && !isset( $this->field['units'] ) ):

				echo '<div class="select_wrapper dimensions-units" original-title="'.__('Units','redux-framework').'">';
				echo '<select data-id="'.$this->field['id'].'" data-placeholder="'.__('Units','redux-framework').'" class="redux-dimensions redux-dimensions-units select'.$this->field['class'].'" original-title="'.__('Units','redux-framework').'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][units]" id="'. $this->field['id'].'_units">';
				
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

			endif;

	  	echo "</fieldset>";

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
			'redux-field-dimensions-js', 
			ReduxFramework::$_url.'inc/fields/dimensions/field_dimensions.min.js', 
			array('jquery', 'select2-js', 'jquery-numeric'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-dimensions-css', 
			ReduxFramework::$_url.'inc/fields/dimensions/field_dimensions.css', 
			time(),
			true
		);
		
	}//function
	
}//class