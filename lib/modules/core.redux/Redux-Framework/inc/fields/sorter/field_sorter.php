<?php

/**
 * Options Sorter Field for Redux Options
 * @author  Yannis - Pastis Glaros <mrpc@pramnoshosting.gr>
 * @url     http://www.pramhost.com
 * @license [http://www.gnu.org/copyleft/gpl.html GPLv3
 *
 * This is actually based on: [SMOF - Slightly Modded Options Framework](http://aquagraphite.com/2011/09/slightly-modded-options-framework/)
 * Original Credits:
 * Author		: Syamil MJ
 * Author URI   	: http://aquagraphite.com
 * License		: GPLv3 - http://www.gnu.org/copyleft/gpl.html
 * Credits		: Thematic Options Panel - http://wptheming.com/2010/11/thematic-options-panel-v2/
  KIA Thematic Options Panel - https://github.com/helgatheviking/thematic-options-KIA
  Woo Themes - http://woothemes.com/
  Option Tree - http://wordpress.org/extend/plugins/option-tree/
 * Twitter: http://twitter.com/syamilmj
 * Website: http://aquagraphite.com
 */
class ReduxFramework_sorter extends ReduxFramework {

    /**
     * Field Constructor.
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     * @since Redux_Options 1.0.0
     */
    function __construct($field = array(), $value = '', $parent) {
        parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
        $this->field = $field;
        $this->value = $value;
        if (!is_array($this->value) && isset($this->field['options'])) {
            $this->value = $this->field['options'];
        }
    }

    /**
     * Field Render Function.
     * Takes the vars and outputs the HTML for the field in the settings
     * @since 1.0.0
     */
    function render() {
        $output = '';
        $value = $this->value;
        $options = $this->field['options'];

        $totalOriginalKeys = array();
        foreach ($options as $group=>$option){
            foreach ($option as $key=>$name){
                $totalOriginalKeys[$key]=$name;
            }
        }


        if (isset($this->value) && is_array($this->value)) {
            $options = $this->value;
            $valueKeys = array();
            foreach ($options as $group => $option) {
                if (!isset($this->field['options'][$group])) {
                    unset($options[$group]);
                }
                else {
                    foreach ($option as $key => $name) {
                        if (!isset($totalOriginalKeys[$key])){
                            unset($options[$group][$key]);
                        }
                        else {
                            $valueKeys[$key]=$name;
                        }
                    }
                }
            }
            if (count($valueKeys) != count($totalOriginalKeys)){
                $options = $this->field['options'];
            }
        }
        ?>
        <script>
            var_opt_name = '<?php echo $this->args['opt_name']; ?>';
        </script>
        <?php
        $output .= '<div id="' . $this->field['id'] . '" class="sorter ' . $this->field['class'] . '">';
        if ($value) {
            foreach ($options as $group => $sortlist) {
                $output .= '<ul id="' . $this->field['id'] . '_' . $group . '" class="sortlist_' . $this->field['id'] . '">';
                $output .= '<h3>' . ucfirst($group) . '</h3>';
                foreach ($sortlist as $key => $list) {
                    $output .= '<input class="sorter-placebo" type="hidden" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $group . '][placebo]" value="placebo">';
                    if ($key != "placebo") {
                        $output .= '<li id="' . $key . '" class="sortee">';
                        $output .= '<input class="position" type="hidden" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $group . '][' . $key . ']" value="' . $list . '">';
                        $output .= $list;
                        $output .= '</li>';
                    }
                }
                $output .= '</ul>';
            }
        }
        $output .= '</div>';
        echo $output;
        echo ($this->field['desc'] != '') ? '<div class="clear"></div><span class="description">' . $this->field['desc'] . '</span>' : '';
    }

    function enqueue() {
        wp_enqueue_script('jquery-ui-sortable');
        wp_register_script('options-sorter', REDUX_URL . 'inc/fields/sorter/field_sorter.min.js', array(
            'jquery'));
        wp_register_style('options-sorter', REDUX_URL . 'inc/fields/sorter/field_sorter.css');
        wp_enqueue_script('options-sorter');
        wp_enqueue_style('options-sorter');
    }

}
