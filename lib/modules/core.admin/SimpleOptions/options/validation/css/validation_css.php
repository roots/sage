<?php
class SOF_Validation_html extends Simple_Options{	
	
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
	 * @since Simple_Options 0.0.7
	*/
	function validate(){
		
		include_once './htmlpurifier/library/HTMLPurifier.auto.php';
		include_once './csstidy/class.csstidy.php';

		// Create a new configuration object
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Filter.ExtractStyleBlocks', TRUE);

		// Create a new purifier instance
		$purifier = new HTMLPurifier($config);

		// Turn off strict warnings (CSSTidy throws some warnings on PHP 5.2+)
		$level = error_reporting(E_ALL & ~E_STRICT);

		// Wrap our CSS in style tags and pass to purifier. 
		// we're not actually interested in the html response though
		$html = $purifier->purify('<style>'.$this->value.'</style>');

		// Revert error reporting
		error_reporting($level);

		// The "style" blocks are stored seperately
		$output_css = $purifier->context->get('StyleBlocks');

		// Get the first style block
		$this->value = $output_css[0];		
				
	}//function
	
}//class
?>