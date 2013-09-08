<?php
class ReduxFramework_radio {

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
        echo '<fieldset>';
        foreach($this->field['options'] as $k => $v){
            //echo '<option value="' . $k . '" ' . selected($this->value, $k, false) . '>' . $v . '</option>';
            echo '<label for="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '">';
            echo '<input type="radio" id="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" ' . $class . ' value="' . $k . '" ' . checked($this->value, $k, false) . '/>';
            echo ' <span>' . $v . '</span>';
            echo '</label><br/>';
        }
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<span class="description">' . $this->field['desc'] . '</span>' : '';
        echo '</fieldset>';
    }
}
