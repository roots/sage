<?php
class ReduxFramework_ace_editor {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
    */
    function __construct($field = array(), $value ='', $parent) {
        $this->field = $field;
		$this->value = trim($value);
		$this->args = $parent->args;
        if( !isset($this->field['mode']) ){
            $this->field['mode'] = 'javascript';
        }
        if( !isset($this->field['theme']) ){
            $this->field['theme'] = 'monokai';
        }
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

        ?>
        <div class="ace-wrapper">
            <textarea name="<?php echo $name; ?>" id="<?php echo $this->field['id']; ?>-textarea" class="ace-editor" data-editor="<?php echo $this->field['id']; ?>-editor" data-mode="<?php echo $this->field['mode']; ?>" data-theme="<?php echo $this->field['theme']; ?>">
                <?php echo $this->value; ?>
            </textarea>
            <pre id="<?php echo $this->field['id']; ?>-editor" class="ace-editor-area"><?php echo htmlspecialchars ($this->value); ?></pre>
        </div>
    <?php
        
    }
    
    /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            wp_enqueue_style(
                'redux-field-ace-editor-css', 
                ReduxFramework::$_url . 'inc/fields/ace_editor/field_ace_editor.css',
                time(),
                true
            );
            wp_register_script(
                'ace-editor',
                ReduxFramework::$_url . 'inc/fields/ace_editor/ace-min-noconflict/ace.js',
                array( 'jquery' ),
                time(),
                true
            );
            wp_enqueue_script(
                'redux-field-ace-editor-js', 
                ReduxFramework::$_url . 'inc/fields/ace_editor/field_ace_editor.js', 
                array( 'ace-editor' ),
                time(),
                true
            );
        }
}
