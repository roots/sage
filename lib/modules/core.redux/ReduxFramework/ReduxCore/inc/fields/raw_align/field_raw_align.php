<?php
class ReduxFramework_raw_align extends ReduxFramework {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 3.0.4
    */
    function __construct( $field = array(), $value ='', $parent ) {
    
        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;
    
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */
    function render() {
        echo '<fieldset id="'.$this->parent->args['opt_name'].'-'.$this->field['id'].'" class="redux-field redux-container-'.$this->field['type'].' '.$this->field['class'].'" data-id="'.$this->field['id'].'">';

        if ( !empty( $this->field['include'] ) && file_exists( $this->field['include'] ) ) {
            include( $this->field['include'] );
        }
        if ( !empty( $this->field['content'] ) && isset( $this->field['content'] ) ) {
            echo $this->field['content'];
        }

        do_action('redux-field-raw-'.$this->parent->args['opt_name'].'-'.$this->field['id']);

        echo '</fieldset>';
    }
}
