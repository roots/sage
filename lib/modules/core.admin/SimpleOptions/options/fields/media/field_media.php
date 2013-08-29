<?php
class Simple_Options_media extends Simple_Options{	
	
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


		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		
		$hide = '';

		if (!empty($this->field['mode']) && $this->field['mode'] == "min") {
			$hide ='hide ';
		}

		// No errors please
		$defaults = array(
			'id' => '',
			'url' => '',
			'width'=>'',
			'height'=>'',
			);

		$this->value = wp_parse_args( $this->value, $defaults );

	  if ( empty($this->value) && !empty($this->field['std']) ) { // If there are standard values and value is empty
	  	if (is_array($this->field['std'])) { // If array
	  		if (!empty($this->field['std']['id'])) { // Set the ID
	  			$this->value['id'] = $this->field['std']['id'];
	  		}
	  		if (!empty($this->field['std']['url'])) { // Set the URL
	  			$this->value['url'] = $this->field['std']['url'];
	  		}	  		
	  	} else {
		  	if (is_numeric($this->field['std'])) { // Check if it's an attachment ID
		  		$this->value['id'] = $this->field['std'];
		  	} else { // Must be a URL
		  		$this->value['url'] = $this->field['std']; 
		  	}	  		
	  	}
	  }

	  if (empty($this->value['url']) && !empty($this->value['id'])) {
	  	$img = wp_get_attachment_image_src( $this->value['id'], 'full' );
	  	$this->value['url'] = $img[0];
	  	$this->value['width'] = $img[1];
	  	$this->value['height'] = $img[2];
	  }

		echo '<input class="'.$hide.'upload'.$class.'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][url]" id="'.$this->args['opt_name'].'['.$this->field['id'].'][url]" value="'. $this->value['url'] .'" readonly="readonly" />';
		echo '<input type="hidden" class="upload-id" name="'.$this->args['opt_name'].'['.$this->field['id'].'][id]" "'.$this->args['opt_name'].'['.$this->field['id'].'][id]" value="'. $this->value['id'] .'" />';
		echo '<input type="hidden" class="upload-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" "'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'. $this->value['height'] .'" />';
		echo '<input type="hidden" class="upload-width" name="'.$this->args['opt_name'].'['.$this->field['id'].'][width]" "'.$this->args['opt_name'].'['.$this->field['id'].'][width]" value="'. $this->value['width'] .'" />';

		//Upload controls DIV
		echo '<div class="upload_button_div">';
		//If the user has WP3.5+ show upload/remove button

			echo '<span class="button media_upload_button" id="'.$this->field['id'].'">Upload</span>';
			$hide = '';
			if ( empty( $this->value['url'] ) || $this->value['url'] == "" ) {
				$hide =' hide';
			}
			echo '<span class="button remove-image'. $hide.'" id="reset_'. $this->field['id'] .'" title="' . $this->field['id'] . '">Remove</span>';

		echo '</div>' . "\n";

		//Preview
		$hide = '';
		if (empty($this->value['url'])) {
			$hide =" hide";
		}

		echo '<div class="screenshot'.$hide.'">';
		echo '<a class="of-uploaded-image" href="'. $this->value['url'] . '">';
		echo '<img class="sof-option-image" id="image_'.$this->field['id'].'" src="'.$this->value['url'].'" alt="" />';
		echo '</a>';
		echo '</div>';
		echo '<div class="clear"></div>' . "\n";
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

		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
		else {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}

		wp_enqueue_script(
			'simple-options-media-js',
			SOF_OPTIONS_URL.'fields/media/field_media.js',
			array('jquery', 'wp-color-picker'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-media-css',
			SOF_OPTIONS_URL.'fields/media/field_media.css',
			time(),
			true
		);

	}//function

}//class
?>