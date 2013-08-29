<?php
class SOF_Validation_preg_replace extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field, $value, $current){
		
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
	 * @since Simple_Options 1.0.0
	*/
	function validate(){
		
		$this->value = preg_replace($this->field['preg']['pattern'], $this->field['preg']['replacement'], $this->value);
				
	}//function
	
}//class
?>