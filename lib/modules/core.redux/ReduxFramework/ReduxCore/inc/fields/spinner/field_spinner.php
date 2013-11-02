<?php

class ReduxFramework_spinner extends ReduxFramework {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 3.0.0
     */
    function __construct($field = array(), $value = '', $parent) {

        parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
        $this->field = $field;
        $this->value = $value;
        //$this->render();
    }

    //function

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 3.0.0
     */
    function render() {

        if (empty($this->field['min'])) {
            $this->field['min'] = 0;
        } else {
            $this->field['min'] = intval($this->field['min']);
        }

        if (empty($this->field['max'])) {
            $this->field['max'] = intval($this->field['min']) + 1;
        } else {
            $this->field['max'] = intval($this->field['max']);
        }

        if (empty($this->field['step']) || $this->field['step'] > $this->field['max']) {
            $this->field['step'] = 1;
        } else {
            $this->field['step'] = intval($this->field['step']);
        }

        if (empty($this->value) && !empty($this->field['default']) && intval($this->field['min']) >= 1) {
            $this->value = intval($this->field['default']);
        }

        if (empty($this->value) && intval($this->field['min']) >= 1) {
            $this->value = intval($this->field['min']);
        }

        if (empty($this->value)) {
            $this->value = 0;
        }

        // Extra Validation
        if ($this->value < $this->field['min']) {
            $this->value = intval($this->field['min']);
        } else if ($this->value > $this->field['max']) {
            $this->value = intval($this->field['max']);
        }

        $params = array(
            'id' => '',
            'min' => '',
            'max' => '',
            'step' => '',
            'val' => '',
            'default' => '',
        );

        $params = wp_parse_args($this->field, $params);
        $params['val'] = $this->value;

        // Don't allow input edit if there's a step
        $readonly = "";
        if (isset($this->field['edit']) && $this->field['edit'] == false) {
            $readonly = ' readonly="readonly"';
        }

        // Use javascript globalization, better than any other method.
        global $wp_scripts;
        $data = $wp_scripts->get_data('redux-field-spinner-js', 'data');

        if (!empty($data)) { // Adding to the previous localize script object
            if (!is_array($data)) {
                $data = json_decode(str_replace('var reduxSpinners = ', '', substr($data, 0, -1)), true);
            }
            foreach ($data as $key => $value) {
                $localized_data[$key] = $value;
            }
            $wp_scripts->add_data('redux-field-spinner-js', 'data', '');
        }
        $localized_data[$this->field['id']] = $params;
        wp_localize_script('redux-field-spinner-js', 'reduxSpinners', $localized_data);

        echo '<input type="text" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" id="' . $this->field['id'] . '" value="' . $this->value . '" class="mini spinner-input' . $this->field['class'] . '"' . $readonly . '/>';
        echo '<div id="' . $this->field['id'] . '-spinner" class="redux_spinner" rel="' . $this->field['id'] . '"></div>';
    }

//function

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 3.0.0
     */
    function enqueue() {

        wp_enqueue_script(
                'redux-typewatch-js', REDUX_URL . 'assets/js/vendor/jquery.typewatch.min.js', array('jquery'), time(), true
        );

        wp_enqueue_script(
                'redux-spinner-js', REDUX_URL . 'inc/fields/spinner/spinner_custom.js', array('jquery'), time(), true
        );

        wp_enqueue_script(
                'redux-field-spinner-js', REDUX_URL . 'inc/fields/spinner/field_spinner.min.js', array('jquery', 'redux-spinner-js', 'jquery-numeric', 'jquery-ui-core', 'jquery-ui-dialog', 'redux-typewatch-js'), time(), true
        );

        wp_enqueue_style(
                'redux-field-spinner-css', REDUX_URL . 'inc/fields/spinner/field_spinner.css', time(), true
        );
    }

//function
}

//class
