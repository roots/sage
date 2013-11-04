<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Info
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @author      Abdullah Almesbahi
 * @version     3.0.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_group')) {

    /**
     * Main ReduxFramework_info class
     *
     * @since       1.0.0
     */
    class ReduxFramework_group extends ReduxFramework {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct($field = array(), $value = '', $parent) {

            parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);

            $this->field = $field;
            $this->value = $value;
            $this->parent = $parent;

        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            if (empty($this->value) || !is_array($this->value)) {
                $this->value = array(
                    array(
                        'slide_title' => __('New', 'redux-framework').' '.$this->field['groupname'],
                        'slide_sort' => '0',
                    )
                );
            }

            echo '<div class="redux-group">';
            echo '<div id="redux-groups-accordion">';
            $x = 0;

            $groups = $this->value;
            foreach ($groups as $group) {

                echo '<div class="redux-groups-accordion-group"><h3><span class="redux-groups-header">' . $group['slide_title'] . '</span></h3>';
                echo '<div>';//according content open
                
                echo '<table style="margin-top: 0;" class="redux-groups-accordion redux-group form-table no-border">';
                
                //echo '<h4>' . __('Group Title', 'redux-framework') . '</h4>';
                echo '<fieldset><input type="hidden" id="' . $this->field['id'] . '-slide_title_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_title]" value="' . esc_attr($group['slide_title']) . '" class="regular-text slide-title" /></fieldset>';
                echo '<input type="hidden" class="slide-sort" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_sort]" id="' . $this->field['id'] . '-slide_sort_' . $x . '" value="' . $group['slide_sort'] . '" />';
                foreach ($this->field['subfields'] as $field) {
                    //we will enqueue all CSS/JS for sub fields if it wasn't enqueued
                    $this->enqueue_dependencies($field['type']);
                    
                    echo '<tr><td>';
                    if(isset($field['class']))
                        $field['class'] .= " group";
                    else
                        $field['class'] = " group";

                    if (!empty($field['title']))
                        echo '<h4>' . $field['title'] . '</h4>';
                    if (!empty($field['subtitle']))
                        echo '<span class="description">' . $field['subtitle'] . '</span>';
                    $value = empty($this->parent->options[$field['id']][$x]) ? " " : $this->parent->options[$field['id']][$x];

                    ob_start();
                    $this->parent->_field_input($field, $value);
                    $content = ob_get_contents();

                    //adding sorting number to the name of each fields in group
                    $name = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
                    $content = str_replace($name, $name . '[' . $x . ']', $content);

                    //we should add $sort to id to fix problem with select field
                    $content = str_replace(' id="'.$field['id'].'-select"', ' id="'.$field['id'].'-select-'.$sort.'"', $content);
                    
                    $_field = apply_filters('redux-support-group',$content, $field, $x);
                    ob_end_clean();
                    echo $_field;
                    
                    echo '</td></tr>';
                }
                echo '</table>';
                echo '<a href="javascript:void(0);" class="button deletion redux-groups-remove">' . __('Delete', 'redux-framework').' '.$this->field['groupname']. '</a>';
                echo '</div></div>';
                $x++;
            }

            echo '</div><a href="javascript:void(0);" class="button redux-groups-add button-primary" rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][slide_title][]">' . __('Add', 'redux-framework') .' '.$this->field['groupname']. '</a><br/>';

            echo '</div>';
            
        }

        function support_multi($content, $field, $sort) {
            //convert name
            $name = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
            $content = str_replace($name, $name . '[' . $sort . ']', $content);
            //we should add $sort to id to fix problem with select field
            $content = str_replace(' id="'.$field['id'].'-select"', ' id="'.$field['id'].'-select-'.$sort.'"', $content);
            return $content;
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since 		1.0.0
         * @access		public
         * @return		void
         */
        public function enqueue() {
            wp_enqueue_script(
                    'redux-field-group-js', ReduxFramework::$_url . 'inc/fields/group/field_group.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'), time(), true
            );

            wp_enqueue_style(
                    'redux-field-group-css', ReduxFramework::$_url . 'inc/fields/group/field_group.css', time(), true
            );
        }

        public function enqueue_dependencies($field_type) {
            $field_class = 'ReduxFramework_' . $field_type;

            if (!class_exists($field_class)) {
                $class_file = apply_filters('redux-typeclass-load', ReduxFramework::$_dir . 'inc/fields/' . $field_type . '/field_' . $field_type . '.php', $field_class);

                if ($class_file) {
                    /** @noinspection PhpIncludeInspection */
                    require_once( $class_file );
                }
            }

            if (class_exists($field_class) && method_exists($field_class, 'enqueue')) {
                $enqueue = new $field_class('', '', $this);
                $enqueue->enqueue();
            }
        }

    }

}