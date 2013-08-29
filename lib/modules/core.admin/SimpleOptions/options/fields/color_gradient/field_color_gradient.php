<?php
class Simple_Options_color_gradient extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
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
	 * @since Simple_Options 1.0.0
	*/
	function render(){
		
		// No errors please
		$defaults = array(
			'from' => '',
			'to' => ''
			);
		$this->value = wp_parse_args( $this->value, $defaults );

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}

		echo '<div class="sof-color-gradient-container" id="'.$this->field['id'].'">';

		echo '<strong>' . __('From ', 'simple-options') . '</strong>&nbsp;<input id="'.$this->field['id'].'-from" name="'.$this->args['opt_name'].'['.$this->field['id'].'][from]" value="'.$this->value['from'].'" class="sof-color ' . $class . '"  type="text" value="' . $this->value . '"  data-default-color="' . $this->field['std']['from'] . '" />';

		echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . __('To ', 'simple-options') . '</strong>&nbsp;<input id="'.$this->field['id'].'-to" name="'.$this->args['opt_name'].'['.$this->field['id'].'][to]" value="'.$this->value['to'].'" class="sof-color ' . $class . '"  type="text" value="' . $this->value . '"  data-default-color="' . $this->field['std']['to'] . '" />';
		
		echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
		
		echo '</div>';
		
	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 1.0.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'simple-options-field-color-js', 
			SOF_OPTIONS_URL.'fields/color/field_color.js', 
			array('jquery', 'wp-color-picker'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-field-color-js', 
			SOF_OPTIONS_URL.'fields/color/field_color.css', 
			time(),
			true
		);		
		
	}//function
	
}//class
?>