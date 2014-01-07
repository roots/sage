<?php

/**
 * Field Select Image
 * 
 * @author Kevin Provance <kevin.provance@gmail.com>
 * @package Wordpress
 * @subpackage ReduxFramework
 * @since 3.1.2
 */

class ReduxFramework_select_image extends ReduxFramework {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
     */
    function __construct($field = array(), $value = '', $parent) {
        //parent::__construct($parent->sections, $parent->args);
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
        
        // If options is NOT empty, the process
        if( !empty( $this->field['options'] ) ) {

            // Strip off the file ext
            if (isset($this->value)){
                $name = explode(".", $this->value);
                $name = str_replace('.' . end($name), '', $this->value);
                $name = basename($name);
                $this->value = trim($name);
            }
            
            // beancounter
            $x = 1;

            // Process width
            if (!empty($this->field['width'])) {
                $width = ' style="width:' . $this->field['width'] . ';"';
            } else {
                $width = ' style="width: 40%;"';
            }

            // Process placeholder
            $placeholder = (isset($this->field['placeholder'])) ? esc_attr($this->field['placeholder']) : __('Select an item', 'redux-framework');

            // Begin the <select> tag
            echo '<select id="' . $this->field['id'] . '-select_image" data-placeholder="' . $placeholder . '" name="' . $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']" class="redux-select-item redux-select-image-item ' . $this->field['class'] . '"' . $width . ' rows="6"' . '>';
            echo '<option></option>';
            

            // Enum through the options array
            foreach( $this->field['options'] as $k => $v ) {
                
                // No array?  No problem!
                if( !is_array( $v ) ){
                    $v = array( 'img' => $v );
                }
                
                // No title set?  Make it blank.
                if( !isset( $v['title'] ) ){
                    $v['title'] = '';
                }

                // No alt?  Set it to title.  We do this so the alt tag shows
                // something.  It also makes HTML/SEO purists happy.
                if( !isset( $v['alt'] ) ){
                    $v['alt'] = $v['title'];
                }
                
                // Set the selected entry
                $selected = selected($this->value, $v['alt'], false);

                // If selected returns something other than a blank space, we
                // found our default/saved name.  Save the array number in a
                // variable to use later on when we want to extract its associted
                // url.
                if('' != $selected){
                    $arrNum = $x;
                }
                
                // Add the option tag, with values.
                echo '<option value="' . $v['img'] . '"' . $selected . '>' . $v['alt'] . '</option>';
                
                // Add a bean
                $x++;
            }
            
            // Close the <select> tag
            echo '</select>';        

            // Some space
            echo '<br /><br />';
            
            // Show the preview image.
            echo '<div>';

            // just in case.  You never know.
            if(!isset($arrNum)){
                $this->value = '';
            }
            
            
            // Set the default image.  To get the url from the default name,
            // we save the array count from the for/each loop, when the default image
            // is mark as selected.  Since the for/each loop starts at one, we must
            // substract one from the saved array number.  We then pull the url
            // out of the options array, and there we go.
            if ('' == $this->value) {
                echo '<img src="#" class="redux-preview-image" style="visibility:hidden;" id="image_' . $this->field['id'] . '">';
            } else {
                echo '<img src=' . $this->field['options'][$arrNum - 1]['img'] . ' class="redux-preview-image" id="image_' . $this->field['id'] . '">';
            }

            // Close the <div> tag.
            echo '</div>';
        } else {
            
            // No options specified.  Really?
            echo '<strong>' . __('No items of this type were found.', 'redux-framework') . '</strong>';            
        }
    }
//function

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since ReduxFramework 1.0.0
     */
    function enqueue() {
        wp_enqueue_script(
            'field-select-image-js', 
            ReduxFramework::$_url . 'inc/fields/select_image/field_select_image.js', 
            array(), 
            time(), 
            true
        );
        
        wp_enqueue_style(
            'redux-field-select-image-css', 
            ReduxFramework::$_url . 'inc/fields/select_image/field_select_image.css', 
            time(), 
            true
        );
    }

//function
}

//class
