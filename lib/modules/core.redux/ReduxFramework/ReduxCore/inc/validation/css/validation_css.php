<?php
class Redux_Validation_css extends ReduxFramework {	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function __construct($field, $value, $current) {
		
		parent::__construct();
		$this->field = $field;
		$this->value = $value;
		$this->current = $current;
		$this->validate();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and validates them
	 *
	 * @since ReduxFramework 3.0.0
	*/
	function validate() {
		
		// Strip all html
		$this->value = wp_filter_nohtml_kses($this->value);
				
	}//function
	
}//class
