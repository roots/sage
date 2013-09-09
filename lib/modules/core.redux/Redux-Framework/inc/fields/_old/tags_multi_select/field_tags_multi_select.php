<?php
class ReduxFramework_tags_multi_select {

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
    }//function

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */
    function render() {
        $class = (isset($this->field['class'])) ? 'class="' . $this->field['class'] . '" ' : '';
        echo '<select id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][]" ' . $class . 'multiple="multiple" >';
        $args = wp_parse_args($this->field['args'], array());
        $tags = get_tags($args);
        foreach($tags as $tag) {
            $selected = (is_array($this->value) && in_array($tag->term_id, $this->value)) ? ' selected="selected"' : '';
            echo '<option value="' . $tag->term_id . '"' . $selected . '>' . $tag->name . '</option>';
        }
        echo '</select>';
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<br/><span class="description">' . $this->field['desc'] . '</span>' : '';
    }
}
