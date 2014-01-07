<?php
class ReduxFramework_text extends ReduxFramework {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
    */
    function __construct( $field = array(), $value ='', $parent ) {
    
        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;
    
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */
    function render() {

        if( !empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
            if (empty($this->field['args'])) {
                $this->field['args'] = array();
            }       
            $this->field['options'] = $this->get_wordpress_data($this->field['data'], $this->field['args']);
            $this->field['class'] .= " hasOptions ";
        }

    	if (empty($this->value) && !empty( $this->field['data'] ) && !empty( $this->field['options'] )) {
    		$this->value = $this->field['options'];
    	}

    	$placeholder = (isset($this->field['placeholder']) && !is_array($this->field['placeholder'])) ? ' placeholder="' . esc_attr($this->field['placeholder']) . '" ' : '';

    	if ( isset( $this->field['options'] ) && !empty( $this->field['options'] ) ) {
    		$placeholder = (isset($this->field['placeholder']) && !is_array($this->field['placeholder'])) ? ' placeholder="' . esc_attr($this->field['placeholder']) . '" ' : '';
			foreach($this->field['options'] as $k => $v){
				if (!empty($placeholder)) {
					$placeholder = (is_array($this->field['placeholder']) && isset($this->field['placeholder'][$k])) ?	' placeholder="' . esc_attr($this->field['placeholder'][$k]) . '" ' : '';
				}
				echo '<label for="' . $this->field['id'] . '-text-'.$k.'"><strong>'.$v.'</strong></label> ';
				echo '<input type="text" id="' . $this->field['id'] . '-text-'.$k.'" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']['.$k.']" ' . $placeholder . 'value="' . esc_attr($this->value[$k]) . '" class="regular-text ' . $this->field['class'] . '" /><br />';
				
			}//foreach
    		
    	} else {
    		
    		echo '<input type="text" id="' . $this->field['id'] . '-text" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']" ' . $placeholder . 'value="' . esc_attr($this->value) . '" class="regular-text ' . $this->field['class'] . '" />';
    	}

    
        
        
    
    }
}
