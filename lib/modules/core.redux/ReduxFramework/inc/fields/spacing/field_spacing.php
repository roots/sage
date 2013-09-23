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
			'units' => '',
			'top'	=> true,
			'bottom'=> true,
			'left'	=> true,
			'right'	=> true,
			);
		$this->field = wp_parse_args( $this->field, $defaults );


		$defaults = array(
			'top'=>'',
			'right'=>'',
			'bottom'=>'',
			'left'=>'',
			'units'=>'px',
		);

		$this->value = wp_parse_args( $this->value, $defaults );
		
	  	echo '<fieldset id="'.$this->field['id'].'" class="redux-spacing-container">';

			/**
			Top
			**/
			if ($this->field['top'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-up icon-large"></i></span><input type="text" class="redux-spacing-top mini'.$this->field['class'].'" placeholder="'.__('Top','redux-framework').'" id="'.$this->field['id'].'-top" name="'.$this->args['opt_name'].'['.$this->field['id'].'][top]" value="'.$this->value['top'].'"></div>';
		  	endif;

			/**
			Right
			**/
			if ($this->field['right'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-right icon-large"></i></span><input type="text" class="redux-spacing-right mini'.$this->field['class'].'" placeholder="'.__('Right','redux-framework').'" id="'.$this->field['id'].'-right" name="'.$this->args['opt_name'].'['.$this->field['id'].'][right]" value="'.$this->value['right'].'"></div>';
		  	endif;

			/**
			Bottom
			**/
			if ($this->field['bottom'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-down icon-large"></i></span><input type="text" class="redux-spacing-bottom mini'.$this->field['class'].'" placeholder="'.__('Bottom','redux-framework').'" id="'.$this->field['id'].'-bottom" name="'.$this->args['opt_name'].'['.$this->field['id'].'][bottom]" value="'.$this->value['bottom'].'"></div>';
		  	endif;

			/**
			Left
			**/
			if ($this->field['left'] === true):
				echo '<div class="field-spacing-input input-prepend"><span class="add-on"><i class="icon-arrow-left icon-large"></i></span><input type="text" class="redux-spacing-left mini'.$this->field['class'].'" placeholder="'.__('Left','redux-framework').'" id="'.$this->field['id'].'-left" name="'.$this->args['opt_name'].'['.$this->field['id'].'][left]" value="'.$this->value['left'].'"></div>';
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
	
}//class