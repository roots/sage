<?php
class ReduxFramework_multi_text {

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
        $class = (isset($this->field['class'])) ? $this->field['class'] : 'regular-text';
        echo '<ul id="' . $this->field['id'] . '-ul">';

        if(isset($this->value) && is_array($this->value)) {
            foreach($this->value as $k => $value) {
                if($value != '') {
                    echo '<li><input type="text" id="' . $this->field['id'] . '-' . $k . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][]" value="' . esc_attr($value) . '" class="' . $class . '" /> <a href="javascript:void(0);" class="redux-opts-multi-text-remove">' . __('Remove', 'redux-framework') . '</a></li>';
                }
            }
        } else {
            echo '<li><input type="text" id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][]" value="" class="' . $class . '" /> <a href="javascript:void(0);" class="redux-opts-multi-text-remove">' . __('Remove', 'redux-framework') . '</a></li>';
        }

        echo '<li style="display:none;"><input type="text" id="' . $this->field['id'] . '" name="" value="" class="' . $class . '" /> <a href="javascript:void(0);" class="redux-opts-multi-text-remove">' . __('Remove', 'redux-framework') . '</a></li>';
        echo '</ul>';
        echo '<a href="javascript:void(0);" class="redux-opts-multi-text-add" rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][]">' . __('Add More', 'redux-framework') . '</a><br/>';
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
            'redux-opts-field-multi-text-js', 
            REDUX_URL . 'inc/fields/multi_text/field_multi_text.js', 
            array('jquery'),
            time(),
            true
        );
    }    
}
