<?php
class ReduxFramework_menu_select {

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
        echo '<select id="' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" ' . $class . ' >';
        if(!isset($this->field['args'])) { $this->field['args'] = array(); }
        $args = wp_parse_args($this->field['args'], array());

        $menus = wp_get_nav_menus($args);
        if($menus) {
            foreach ($menus as $menu) {
                echo '<option value="' . $menu->term_id . '"' . selected($this->value, $menu->term_id, false) . '>' . $menu->name . '</option>';
            }
        }

        echo '</select>';
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? ' <span class="description">' . $this->field['desc'] . '</span>' : '';
    }
}
