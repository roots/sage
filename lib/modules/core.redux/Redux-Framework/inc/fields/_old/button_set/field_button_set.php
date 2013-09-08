<?php
class ReduxFramework_button_set {

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
        $class = (isset($this->field['class'])) ? 'class="' . $this->field['class'] . '" ' : '';
        echo '<fieldset class="buttonset">';
            foreach($this->field['options'] as $k => $v) {
                echo '<input type="radio" id="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" ' . $class . ' value="' . $k . '" ' . checked($this->value, $k, false) . '/>';
                echo '<label for="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '">' . $v . '</label>';
            }
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '&nbsp;&nbsp;<span class="description">' . $this->field['desc'] . '</span>' : '';
        echo '</fieldset>';
    }

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 1.0.0
    */
    function enqueue() {
        wp_enqueue_style('redux-opts-jquery-ui-css');
        wp_enqueue_script(
            'redux-opts-field-button_set-js', 
            REDUX_URL . 'inc/fields/button_set/field_button_set.js', 
            array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'),
            time(),
            true
        );
    }
}
