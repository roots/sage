<?php
class ReduxFramework_raw {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 3.0.4
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
    
        echo '</td></tr></table><table class="form-table no-border redux-group-table" style="margin-top: 0;"><tbody><tr><td>';
        echo '<fieldset id="'.$this->parent->args['opt_name'].'-'.$this->field['id'].'" class="redux-field redux-container-'.$this->field['type'].' '.$this->field['class'].'" data-id="'.$this->field['id'].'">';

        if ( !empty( $this->field['include'] ) && file_exists( $this->field['include'] ) ) {
            include( $this->field['include'] );
        }

        do_action('redux-field-raw-'.$this->parent->args['opt_name'].'-'.$this->field['id']);

        echo '</fieldset>';
        echo '</td></tr></table><table class="form-table no-border" style="margin-top: 0;"><tbody><tr><th></th><td>';        

    }
}
