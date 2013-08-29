<?php
class Simple_Options_images extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field = array(), $value = '', $parent = ''){
		
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
		
		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		
		echo '<fieldset>';
			
		if (!empty($this->field['options'])) {

			echo '<ul class="sof-images">';
			
			foreach($this->field['options'] as $k => $v){

				if (!is_array($v)) {
					$v = array('img'=>$v);
				}

				if (!isset($v['title'])) {
					$v['title'] = "";
				}
				if (!isset($v['alt'])) {
					$v['alt'] = $v['title'];
				}			

				$style = "";
				if (!empty($this->field['width'])) {
					$style .= 'width: '.$this->field['width'];
					if (is_numeric($this->field['width'])) {
						$style .= "px";
					}
					$style .= ";";
				}	
				if (!empty($this->field['height'])) {
					$style .= 'height: '.$this->field['height'];
					if (is_numeric($this->field['height'])) {
						$style .= "px";
					}
					$style .= ";";
				}	

				$selected = (checked($this->value, $k, false) != '')?' sof-images-selected':'';

				$presets = "";
				if (!empty($this->field['presets']) && $this->field['presets'] && !empty($v['presets'])) {
					
					if (!is_array($v['presets'])) {
						$v['presets'] = json_decode($v['presets'], true);
					}
					$v['presets']['simple-options-backup'] = 1;

					$presets = ' data-presets="'.htmlspecialchars(json_encode($v['presets']), ENT_QUOTES, 'UTF-8').'"';
					$selected = "";
					$class .= " sof-presets";
				}				

				
				echo '<li class="sof-images">';
				echo '<label class="'.$selected.' sof-images-'.$this->field['id'].'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				$theValue = $k;
				if (!empty($this->field['tiles']) && $this->field['tiles'] == true) {
					$theValue = $v['img'];
				}
				echo '<input type="radio" class="noUpdate' . $class . '" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$theValue.'" '.checked($this->value, $k, false).$presets.'/>';
				if (!empty($this->field['tiles']) && $this->field['tiles'] == true) {
					echo '<span class="tiles" style="background-image: url('.$v['img'].');">&nbsp;</span>';
				} else {
					echo '<img src="'.$v['img'].'" alt="'.$v['alt'].'" style="'.$style.'"'.$presets.' />';	
				}
				
				if ($v['title'] != "") {
					echo '<br /><span>'.$v['title'].'</span>';	
				}
				echo '</label>';		
				echo '</li>';
			}//foreach
				
			echo '</ul>';		
			if (!empty($this->field['presets']) && $this->field['presets']) {
				echo '<div class="sof-presets-bar"><input type="button" class="sof-save-preset button-primary" value="Load Preset"></div>';
			}

		}

		echo '</fieldset>';

		echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
		
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
			'simple-options-field-images-js', 
			SOF_OPTIONS_URL.'fields/images/field_images.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-field-images-css', 
			SOF_OPTIONS_URL.'fields/images/field_images.css',
			time(),
			true
		);		
		
	}//function
	
}//class
?>