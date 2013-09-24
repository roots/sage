<?php
class ReduxFramework_sortable {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since Redux_Options 2.0.1
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
     * @since Redux_Options 2.0.1
    */
    function render() {

    	if ( empty( $this->field['mode'] ) ) {
    		$this->field['mode'] = "text";
    	}

		if ( $this->field['mode'] != "checkbox" && $this->field['mode'] != "text"  ) {
    		$this->field['mode'] = "text";
    	}    	

        $class = (isset($this->field['class'])) ? $this->field['class'] : '';
        $options = $this->field['options'];

        echo '<fieldset id="'.$this->field['id'].'" class="redux-sortable-container">';

            echo '<ul id="'.$this->field['id'].'-list" class="redux-sortable ' . $class . '">';
                if (isset($this->value) && is_array($this->value)) {
                    foreach ($this->value as $k => $nicename) {
                        $value_display = isset($this->value[$k]) ? $this->value[$k] : '';
                        echo '<li>';
                        echo '<label for="' . $this->field['id'] . '[' . $k . ']"><strong>' . $options[$k] . ':</strong></label>';
                        echo '<input class="' . $class . '" type="'.$this->field['mode'].'" id="' . $this->field['id'] . '[' . $k . ']" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" value="' . esc_attr($value_display) . '" placeholder="' . $nicename . '" />';
                        echo '<span class="compact drag"><i class="icon-move icon-large"></i></span>';
                        echo '</li>';
                    }
                } else {
                    foreach ($options as $k => $nicename) {
                        $value_display = isset($this->value[$k]) ? $this->value[$k] : '';
                        echo '<li>';
                        echo '<label for="' . $this->field['id'] . '[' . $k . ']"><strong>' . $nicename . ': </strong></label>';
                        echo '<input class="' . $class . '" type="'.$this->field['mode'].'" id="' . $this->field['id'] . '[' . $k . ']" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" value="' . esc_attr($value_display) . '" placeholder="' . $nicename . '" />';
                        echo '<span class="drag"><i class="icon-move icon-large"></i></span>';
                        echo '</li>';
                    }
                }
            echo '</ul>';
            echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
        echo "</fieldset>";
    }

    function enqueue() {

        wp_enqueue_script(
            'redux-field-sortable-js',
            REDUX_URL . 'inc/fields/sortable/field_sortable.min.js',
            array('jquery'),
            time(),
            true
        );


		wp_enqueue_style(
			'redux-field-sortable-css', 
			REDUX_URL.'inc/fields/sortable/field_sortable.css', 
			time(),
			true
		);	

    }
}