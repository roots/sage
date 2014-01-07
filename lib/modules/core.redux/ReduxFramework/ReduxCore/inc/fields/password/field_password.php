<?php
/**
 * Class ReduxFramework_password
 */
class ReduxFramework_password extends ReduxFramework {

	/**
	 * Field Constructor.
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 * @since ReduxFramework 1.0.1
	 */
    function __construct( $field = array(), $value ='', $parent ) {
    
		//parent::__construct( $parent->sections, $parent->args );
		$this->parent = $parent;
		$this->field = $field;
		$this->value = $value;
    
    }

	/**
	 * Field Render Function.
	 * Takes the vars and outputs the HTML for the field in the settings
	 * @since ReduxFramework 1.0.1
	 */
	function render() {
		if ( ! empty( $this->field['username'] ) && $this->field['username'] === true ) {
			$this->_render_combined_field();
		}
		else {
			$this->_render_single_field();
		}
	}

	/**
	 * This will render a combined User/Password field
	 * @since ReduxFramework 3.0.9
	 * @example
	 * <code>
	 * array(
	 *    'id'          => 'smtp_account',
	 *    'type'        => 'password',
	 *    'username'    => true,
	 *    'title'       => 'SMTP Account',
	 *    'placeholder' => array('username' => 'Username')
	 *    )
	 * </code>
	 */
	private function _render_combined_field() {

		$defaults    = array(
			'username'    => '',
			'password'    => '',
			'placeholder' => array(
				'password' => __( 'Password', 'redux-framework' ),
				'username' => __( 'Username', 'redux-framework' )
			)
		);
		$this->value = wp_parse_args( $this->value, $defaults );


		if ( ! empty( $this->field['placeholder'] ) ) {
			if ( is_array( $this->field['placeholder'] ) ) {
				if( ! empty( $this->field['placeholder']['password'] ) ) {
					$this->value['placeholder']['password'] = $this->field['placeholder']['password'];
				}
				if ( ! empty( $this->field['placeholder']['username'] ) ) {
					$this->value['placeholder']['username'] = $this->field['placeholder']['username'];
				}
			} else {
				$this->value['placeholder']['password'] = $this->field['placeholder'];
			}
		}

		// Username field
		echo '<input type="input" autocomplete="off" placeholder="' . $this->value['placeholder']['username'] . '" id="' . $this->field['id'] . '[username]" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][username]" value="' . esc_attr( $this->value['username'] ) . '" class="regular-text ' . $this->field['class'] . '" />&nbsp;';

		// Password field
		echo '<input type="password" autocomplete="off" placeholder="' . $this->value['placeholder']['password'] . '" id="' . $this->field['id'] . '[password]" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][password]" value="' . esc_attr( $this->value['password'] ) . '" class="regular-text ' . $this->field['class'] . '" />';

	}

	/**
	 * This will render a single Password field
	 * @since ReduxFramework 3.0.9
	 * @example
	 * <code>
	 * array(
	 *    'id'    => 'smtp_password',
	 *    'type'  => 'password',
	 *    'title' => 'SMTP Password'
	 *    )
	 * </code>
	 */
	private function _render_single_field() {
		echo '<input type="password" id="' . $this->field['id'] . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . esc_attr( $this->value ) . '" class="regular-text ' . $this->field['class'] . '" />';
	}

}
