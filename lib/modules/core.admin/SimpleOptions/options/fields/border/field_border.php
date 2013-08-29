<?php
class Simple_Options_border extends Simple_Options{	
	
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
			'color' => '',
			'style' => '',
			'size' => '',
			);
		$this->value = wp_parse_args( $this->value, $defaults );
		$this->field['std'] = wp_parse_args( $this->field['std'], $defaults );	

		if (empty($this->field['min'])) {
			$this->field['min'] = 0;
		}
		if (empty($this->field['max'])) {
			$this->field['max'] = 10;
		}		
		
		echo '<div class="sof-border-container">';

		$options = array(''=>'None', 'solid'=>'Solid', 'dashed'=>'Dashed', 'dotted'=>'Dotted');

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		echo '<div class="sof-border">';
		
			echo '<select original-title="'.__('Border size','simple-options').'" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]" class="tips sof-border-size mini'.$class.'" rows="6">';
				for ($k = $this->field['min']; $k <= $this->field['max']; $k++) {
					echo '<option value="'.$k.'"'.selected($this->value['size'], $k, false).'>'.$k.'</option>';
				}//foreach
			echo '</select>';	
			echo '<select original-title="'.__('Border style','simple-options').'" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][style]" class="tips sof-border-style'.$class.'" rows="6">';
				foreach($options as $k => $v){
					echo '<option value="'.$k.'"'.selected($this->value['style'], $k, false).'>'.$v.'</option>';
				}//foreach
			echo '</select>';	
			echo '<input name="'.$this->args['opt_name'].'['.$this->field['id'].'][color]" id="' . $this->field['id'] . '-color" class="sof-border-color sof-color ' . $class . '"  type="text" value="' . $this->value['color'] . '"  data-default-color="' . $this->field['std']['color'] . '" />';
			
			echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
			
			echo '</div>';
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
			'simple-options-field-color-css', 
			SOF_OPTIONS_URL.'fields/color/field_color.css', 
			time(),
			true
		);		
		
		wp_enqueue_style(
			'simple-options-field-border-css', 
			SOF_OPTIONS_URL.'fields/border/field_border.css', 
			time(),
			true
		);		

	}//function
	
}//class
?>