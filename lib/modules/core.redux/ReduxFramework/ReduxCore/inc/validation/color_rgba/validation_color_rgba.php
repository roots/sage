<?php
class Redux_Validation_color_rgba extends ReduxFramework {	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 3.0.4
	*/
	function __construct($field, $value, $current) {
		
		parent::__construct();
		$this->field = $field;
		$this->field['msg'] = (isset($this->field['msg']))?$this->field['msg']:__('This field must be a valid color value.', 'redux');
		$this->value = $value;
		$this->current = $current;
		$this->validate();
		
	}//function

	/**
	 * Validate Color to RGBA
	 *
	 * Takes the user's input color value and returns it only if it's a valid color.
	 *
	 * @since ReduxFramework 3.0.3
	*/	
	function validate_color_rgba($color) {

		if ($color == "transparent") {
			return $color;
		}

		$color = str_replace('#','', $color);
		if (strlen($color) == 3) {
			$color = $color.$color;
		}
		if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
			$color = '#' . $color;
		}
	  
	  	return $this->hex2rgba($color);

	}//function


	/**
	 * Field Render Function.
	 *
	 * Takes the color hex value and converts to a rgba.
	 *
	 * @since ReduxFramework 3.0.4
	 * @author @mekshq, http://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
	*/
	function hex2rgba($color, $opacity = false) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if(empty($color))
	          return $default; 

		//Sanitize $color if "#" is provided 
	        if ($color[0] == '#' ) {
	        	$color = substr( $color, 1 );
	        }

	        //Check if color has 6 or 3 characters and get values
	        if (strlen($color) == 6) {
	                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	        } elseif ( strlen( $color ) == 3 ) {
	                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	        } else {
	                return $default;
	        }

	        //Convert hexadec to rgb
	        $rgb =  array_map('hexdec', $hex);

	        //Check if opacity is set(rgba or rgb)
	        if($opacity){
	        	if(abs($opacity) > 1)
	        		$opacity = 1.0;
	        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
	        } else {
	        	$output = 'rgb('.implode(",",$rgb).')';
	        }

	        //Return rgb(a) color string
	        return $output;
	}

	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since ReduxFramework 3.0.0
	*/
	function validate() {
		
		if(is_array($this->value)) { // If array
			foreach($this->value as $k => $value){
				$this->value[$k] = $this->validate_color_rgba($value);
			}//foreach
		} else { // not array
			$this->value = $this->validate_color_rgba($this->value);
		} // END array check
		
	}//function
	
}//class