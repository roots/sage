<?php
class ReduxFramework_select_hide_below {

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
        echo '<select id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" class="' . $class . ' redux-opts-select-hide-below" >';
        foreach($this->field['options'] as $k => $v) {
            echo '<option value="' . $k . '" ' . selected($this->value, $k, false) . ' data-allow="' . $v['allow'] . '">' . $v['name'] . '</option>';
        }
        echo '</select>';
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? ' <span class="description">' . $this->field['desc'] . '</span>' : '';
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
            'redux-opts-select-hide-below-js', 
            REDUX_URL . 'inc/fields/select_hide_below/field_select_hide_below.js', 
            array('jquery'),
            time(),
            true
        );
    }
}
