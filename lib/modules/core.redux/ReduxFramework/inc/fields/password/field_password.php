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
			);
		$this->value = wp_parse_args( $this->value, $defaults );
	}
	$this->field['username'] = true;
	if (!empty($this->field['username']) && $this->field['username'] === true ) {
		echo '<input type="input" autocomplete="off" placeholder="'.__( 'Username', 'redux-framework' ).'" id="' . $this->field['id'] . '[username]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][username]" value="' . esc_attr($this->value['username']) . '" class="regular-text ' . $this->field['class'] . '" style="margin-right: 5px;" />';
					echo '<input type="password" autocomplete="off" placeholder="'.__( 'Password', 'redux-framework' ).'" id="' . $this->field['id'] . '[password]" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][password]" value="' . esc_attr($this->value['password']) . '" class="regular-text ' . $this->field['class'] . '" />';
	} else {
		echo '<input type="password" autocomplete="off" placeholder="'.__( 'Password', 'redux-framework' ).'" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . esc_attr($this->value) . '" class="' . $this->field['class'] . '" />';
	}
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<br /><span class="description">' . $this->field['desc'] . '</span>' : '';
    }
}
