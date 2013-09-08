<?php
class ReduxFramework_color {

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

        if(get_bloginfo('version') >= '3.5') {
            echo '<input type="text" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . $this->value . '" class="' . $class . ' popup-colorpicker" style="width: 70px;" data-default-color="' . esc_attr($this->value) . '"/>';
            echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? ' <span class="description">' . $this->field['desc'] . '</span>' : '';
        } else {
            echo '<div class="farb-popup-wrapper">';
            echo '<input type="text" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . $this->value . '" class="' . $class . ' popup-colorpicker" style="width:70px;"/>';
            echo '<div class="farb-popup"><div class="farb-popup-inside"><div id="' . $this->field['id'] . 'picker" class="color-picker"></div></div></div>';
            echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? ' <span class="description">' . $this->field['desc'] . '</span>' : '';
            echo '</div>';
        }
    }

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 1.0.0
    */
    function enqueue() {
        if(get_bloginfo('version') >= '3.5') {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script(
                'redux-opts-field-color-js',
                REDUX_URL . 'inc/fields/color/field_color.js',
                array('wp-color-picker'),
                time(),
                true
            );
        } else {
            wp_enqueue_script(
                'redux-opts-field-color-js', 
                REDUX_URL . 'inc/fields/color/field_color_farb.js', 
                array('jquery', 'farbtastic'),
                time(),
                true
            );
        }
    }
}
