<?php
class ReduxFramework_textarea {

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

        $name = $this->args['opt_name'] . '[' . $this->field['id'] . ']';
        $this->field['placeholder'] = isset($this->field['placeholder']) ? $this->field['placeholder'] : "";
        $this->field['rows'] = isset($this->field['rows']) ? $this->field['rows'] : 6;

        ?><textarea name="<?php echo $name; ?>" id="<?php echo $this->field['id']; ?>-textarea" placeholder="<?php echo esc_attr($this->field['placeholder']); ?>" class="large-text <?php echo $this->field['class']; ?>" rows="<?php echo $this->field['rows']; ?>"><?php echo $this->value; ?></textarea><?php
        
    }
}
