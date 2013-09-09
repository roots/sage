<?php
class ReduxFramework_checkbox_hide_below {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
    */
    function __construct($field = array(), $value ='', $parent) {
        $this->field = $field;
		$this->value = $value;
		$this->args = $parent->args;
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */
    function render() {
	$class = (isset($this->field['class'])) ? $this->field['class'] : '';
	$switch = isset($this->field['switch']) ? $this->field['switch'] : false;		
	$next_to_hide = (isset($this->field['next_to_hide'])) ? $this->field['next_to_hide'] : '1';
		
	echo '<label for="' . $this->field['id'] . '"';
	if ($switch) echo ' class="switch_wrap"';
	echo '>';
	echo '<input data-amount="' . $next_to_hide . '" type="checkbox" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="1" class="' . $class . ' redux-opts-checkbox-hide-below" ' . checked($this->value, '1', false) . ' /> ';
	if($switch) { echo '<div class="switch"><span class="bullet"></span></div>'; } 
	if (isset($this->field['desc']) && !empty($this->field['desc'])) echo $this->field['desc'];
	echo '</label>';
    }

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 1.0.0
    */
    function enqueue() {
        wp_enqueue_script(
            'redux-opts-checkbox-hide-below-js', 
            REDUX_URL . 'inc/fields/checkbox_hide_below/field_checkbox_hide_below.js', 
            array('jquery'),
            time(),
            true
        );
    }
}
