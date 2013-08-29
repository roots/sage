<?php
class Simple_Options_switch extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 0.0.4
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
	 * @since Simple_Options 0.0.4
	*/
	function render(){

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}

		$fold = '';
		//if (array_key_exists("folds",$this->value)) $fold="s_fld ";
		
		$cb_enabled = $cb_disabled = '';//no errors, please
					
		$val = intval($this->value);

		//Get selected
		if ( (int) $this->value == 1 ){
			$cb_enabled = ' selected';
			$cb_disabled = '';
		}else {
			$cb_enabled = '';
			$cb_disabled = ' selected';
		}
		
		//Label ON
		if(!isset($this->field['on'])){
			$on = "On";
		}else{
			$on = $this->field['on'];
		}
		
		//Label OFF
		if(!isset($this->field['off'])){
			$off = "Off";
		}else{
			$off = $this->field['off'];
		}
		
		echo '<div class="switch-options">';
			echo '<label class="'.$fold.'cb-enable'. $cb_enabled .'" data-id="'.$this->field['id'].'"><span>'. $on .'</span></label>';
			echo '<label class="'.$fold.'cb-disable'. $cb_disabled .'" data-id="'.$this->field['id'].'"><span>'. $off .'</span></label>';
			echo '<input type="hidden" class="'.$fold.'checkbox checkbox-input'.$class.'" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$this->value.'" />';
		echo '</div>';

		echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
		
	}//function
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 0.0.4
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'sof-switch-js', 
			SOF_OPTIONS_URL.'fields/switch/field_switch.js', 
			array('jquery'),
			time(),
			true
		);		

		wp_enqueue_style(
			'sof-switch-css', 
			SOF_OPTIONS_URL.'fields/switch/field_switch.css', 
			time(),
			true
		);		

	}//function

}//class
?>