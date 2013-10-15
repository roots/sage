<?php
class ReduxFramework_select extends ReduxFramework{	
	
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

		
        if( !empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
			if (empty($this->field['args'])) {
				$this->field['args'] = array();
			}
			if ($this->field['data'] == "elusive-icons" || $this->field['data'] == "elusive-icon" || $this->field['data'] == "elusive" ) {
       			$icons_file = REDUX_DIR.'inc/fields/select/elusive-icons.php';
       			$icons_file = apply_filters('redux-font-icons-file',$icons_file);
       			if(file_exists($icons_file))
       				require_once $icons_file;
			}        	
        	$this->field['options'] = $parent->get_wordpress_data($this->field['data'], $this->field['args']);
        }

	}//function
	


	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function render(){

		if ( !empty($this->field['data']) && ( $this->field['data'] == "elusive-icons" || $this->field['data'] == "elusive-icon" || $this->field['data'] == "elusive" ) ) {
       		$this->field['class'] = " font-icons";
		}//if

		if (!empty($this->field['options'])) {
			if (isset($this->field['multi']) && $this->field['multi']) {
				$multi = ' multiple="multiple"';
			} else {
				$multi = "";
			}
			
			if (!empty($this->field['width'])) {
				$width = ' style="'.$this->field['width'].'"';
			} else {
				$width = ' style="width: 40%;"';
			}	

			$nameBrackets = "";
			if (!empty($multi)) {
				$nameBrackets = "[]";
			}

			$placeholder = (isset($this->field['placeholder'])) ? esc_attr($this->field['placeholder']) : __( 'Select an item', 'redux-framework' );
	
			echo '<select'.$multi.' id="'.$this->field['id'].'" data-placeholder="'.$placeholder.'" name="'.$this->args['opt_name'].'['.$this->field['id'].']'.$nameBrackets.'" class="redux-select-item '.$this->field['class'].'"'.$width.' rows="6">';
				echo '<option></option>';
				foreach($this->field['options'] as $k => $v){
					if (is_array($this->value)) {
						$selected = (is_array($this->value) && in_array($k, $this->value))?' selected="selected"':'';					
					} else {
						$selected = selected($this->value, $k, false);
					}
					echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
				}//foreach
			echo '</select>';			
		} else {
			echo '<strong>'.__('No items of this type were found.', 'redux-framework').'</strong>';
		}

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
			'field-select-js', 
			REDUX_URL.'inc/fields/select/field_select.min.js', 
			array('jquery', 'select2-js'),
			time(),
			true
		);		

	}//function

}//class