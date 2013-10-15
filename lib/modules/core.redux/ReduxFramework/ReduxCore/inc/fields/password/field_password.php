<?php
class ReduxFramework_password {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.1
    */
    function __construct( $field = array(), $value ='', $parent ) {
      $this->field = $field;
			$this->value = $value;
			$this->args = $parent->args;
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.1
    */
    function render() {
    	if (!empty($this->field['username']) && $this->field['username'] === true ) {
    		$defaults = array(
    				'username'=>'',
    				'password'=>'',
                    'placeholder' => array('password'=>__( 'Password', 'redux-framework' ), 'username'=>__( 'Username', 'redux-framework' ))
    			);
    		$this->value = wp_parse_args( $this->value, $defaults );
    	}
    
        if ( !empty($this->field['placeholder'] ) ) {
            if ( is_array( $this->field['placeholder'] ) && !empty( $this->field['placeholder']['password'] ) ) {
                $this->value['placeholder']['password'] = $this->field['placeholder']['password'];
            }
            if ( is_array( $this->field['placeholder'] ) && !empty( $this->field['placeholder']['username'] ) ) {
                $this->value['placeholder']['username'] = $this->field['placeholder']['username'];
            }                
        } else {
            $this->value['placeholder']['password'] = $this->field['placeholder'];
        }

        if (!empty($this->field['username']) && $this->field['username'] === true ) {
    		echo '<input type="input" autocomplete="off" placeholder="'.$this->value['placeholder']['username'].'" id="' . $this->field['id'] . '[username]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][username]" value="' . esc_attr($this->value['username']) . '" class="regular-text ' . $this->field['class'] . '" style="margin-right: 5px;" />';
    		echo '<input type="password" autocomplete="off" placeholder="'.$this->value['placeholder']['password'].'" id="' . $this->field['id'] . '[password]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][password]" value="' . esc_attr($this->value['password']) . '" class="regular-text ' . $this->field['class'] . '" />';
    	} else {
    		echo '<input type="password" autocomplete="off" placeholder="'.$this->value['placeholder']['password'].'" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . esc_attr($this->value) . '" class="' . $this->field['class'] . '" />';
    	}
        
    }
}
