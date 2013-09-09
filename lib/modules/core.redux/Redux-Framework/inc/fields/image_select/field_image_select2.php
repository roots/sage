<?php
class ReduxFramework_image_select extends ReduxFramework{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
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
	 * @since ReduxFramework 1.0.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		
		echo '<fieldset>';
			
		if (!empty($this->field['options'])) {

			echo '<ul class="redux-image-select">';
			
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

				$theValue = $k;
				if (!empty($this->field['tiles']) && $this->field['tiles'] == true) {
					$theValue = $v['img'];
				}

				$selected = (checked($this->value, $theValue, false) != '')?' redux-image-select-selected':'';

				$presets = "";
				if (!empty($this->field['presets']) && $this->field['presets'] && !empty($v['presets'])) {
					
					if (!is_array($v['presets'])) {
						$v['presets'] = json_decode($v['presets'], true);
					}
					$v['presets']['redux-backup'] = 1;

					$presets = ' data-presets="'.htmlspecialchars(json_encode($v['presets']), ENT_QUOTES, 'UTF-8').'"';
					$selected = "";
					$class .= " redux-presets";
				}				

				
				echo '<li class="redux-image-select">';
				echo '<label class="'.$selected.' redux-image-select-'.$this->field['id'].'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';


				echo '<input type="radio" class="noUpdate' . $class . '" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$theValue.'" '.checked($this->value, $theValue, false).$presets.'/>';
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
				echo '<div class="redux-presets-bar"><input type="button" class="redux-save-preset button-primary" value="Load Preset"></div>';
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
	 * @since ReduxFramework 1.0.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'redux-field-image-select-js', 
			REDUX_URL.'inc/fields/image_select/field_image_select.min.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-image-select-css', 
			REDUX_URL.'inc/fields/image_select/field_image_select.css',
			time(),
			true
		);		
		
	}//function
	
}//class
?>