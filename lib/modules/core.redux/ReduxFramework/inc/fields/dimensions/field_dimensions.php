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
			'units' => '',
			'width'	=> true,
			'height'=> true,
			);
		$this->field = wp_parse_args( $this->field, $defaults );


		$defaults = array(
			'width'=>'',
			'height'=>'',
			'units'=>'px',
		);

		$this->value = wp_parse_args( $this->value, $defaults );
		
	  	echo '<fieldset id="'.$this->field['id'].'" class="redux-dimensions-container" data-id="'.$this->field['id'].'">';

			/**
			Width
			**/
			if ($this->field['width'] === true):
				echo '<div class="field-dimensions-input input-prepend"><span class="add-on"><i class="icon-resize-horizontal icon-large"></i></span><input type="text" class="redux-dimensions-width mini'.$this->field['class'].'" placeholder="'.__('Width','redux-framework').'" id="'.$this->field['id'].'-width" name="'.$this->args['opt_name'].'['.$this->field['id'].'][width]" value="'.$this->value['width'].'"></div>';
		  	endif;

			/**
			Height
			**/
			if ($this->field['height'] === true):
				echo '<div class="field-dimensions-input input-prepend"><span class="add-on"><i class="icon-resize-vertical icon-large"></i></span><input type="text" class="redux-dimensions-height mini'.$this->field['class'].'" placeholder="'.__('Height','redux-framework').'" id="'.$this->field['id'].'-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'.$this->value['height'].'"></div>';
		  	endif;



			/** 
			Units
			**/

			if ( $this->field['units'] !== false ):

				echo '<div class="select_wrapper spacing-units" original-title="'.__('Units','redux-framework').'">';
				echo '<select data-placeholder="'.__('Units','redux-framework').'" class="redux-spacing redux-spacing-units select'.$this->field['class'].'" original-title="'.__('Units','redux-framework').'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][units]" id="'. $this->field['id'].'_units">';
				
				$testUnits = array('px', 'em', '%');

				if ( in_array($this->field['units'], $testUnits) ) {
					echo '<option value="'.$this->field['units'].'" selected="selected">'.$this->field['units'].'</option>';
				} else {

					echo '<option value="px" '.selected($this->value['units'], 'px', false).'>px</option>';
				 	echo '<option value="em"'.selected($this->value['units'], 'em', false).'>em</option>';
				 	echo '<option value="%"'.selected($this->value['units'], '%', false).'>%</option>';
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
			REDUX_URL.'inc/fields/dimensions/field_dimensions.min.js', 
			array('jquery', 'select2-js', 'jquery-numeric'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-dimensions-css', 
			REDUX_URL.'inc/fields/dimensions/field_dimensions.css', 
			time(),
			true
		);	
			
		
	}//function
	
}//class